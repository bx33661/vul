# Contributing

Contributions that improve reproducibility, technical accuracy, mitigations, or
documentation are welcome.

## Before opening a change

1. Keep testing confined to systems you own or are permitted to assess.
2. Do not include credentials, session identifiers, personal data, or private
   target details in issues, commits, logs, or screenshots.
3. Base affected-version and vulnerability claims on reproducible evidence.
4. Keep `README.md` and `README_zh.md` aligned when changing shared content.
5. Avoid adding firmware images or other large binaries unless they are essential
   evidence and their source, checksum, and redistribution status are documented.

## Local checks

```bash
python3 -m pip install -r requirements.txt
python3 -m py_compile poc.py
python3 poc.py --help
docker compose -f environment/docker-compose.yml config --quiet
```

Use a focused commit message and explain the evidence behind research changes in
the pull request.
