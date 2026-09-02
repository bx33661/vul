# CVE-2026-30039

Security advisory materials for `CVE-2026-30039`, a symlink-based archive extraction issue in `rarfile` affecting versions `4.2` and earlier.

[English advisory](./CVE-2026-30039.md) | [中文版本](./CVE-2026-30039.zh-CN.md)

## Overview

`rarfile` extracts symbolic links from attacker-controlled RAR archives without validating whether the embedded symlink target remains within the intended extraction directory. A crafted archive can therefore create a symlink that points to an absolute path or escapes the destination directory.

If the extracted symlink is later dereferenced by application logic, a user, or downstream tooling, arbitrary local files on the host may be disclosed.

## Metadata

| Field | Value |
| --- | --- |
| CVE | `CVE-2026-30039` |
| Product | `rarfile` |
| Vendor / Maintainer | Marko Kreen |
| Affected versions | `<= 4.2` |
| Vulnerability type | Unsafe symlink extraction / symlink target traversal |
| Primary CWE | `CWE-59` |
| Impact | Arbitrary local file disclosure after symlink dereference |
| Public status | CVE assigned |

## Repository Contents

- [CVE-2026-30039.md](./CVE-2026-30039.md): English advisory
- [CVE-2026-30039.zh-CN.md](./CVE-2026-30039.zh-CN.md): Chinese advisory

## Short Description

`rarfile` through 4.2 does not validate symbolic link targets during archive extraction. A crafted RAR archive can create a symlink that points to an absolute path or outside the destination directory, which may lead to arbitrary local file disclosure if the extracted link is later dereferenced.

## References

- [rarfile GitHub repository](https://github.com/markokr/rarfile)
- [rarfile on PyPI](https://pypi.org/project/rarfile/)

## Credit

Discovered by `bx33661`.
