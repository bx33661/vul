## Summary

Describe the research, documentation, environment, or code change.

## Validation

- [ ] `python3 -m py_compile poc.py`
- [ ] `python3 poc.py --help`
- [ ] `docker compose -f environment/docker-compose.yml config --quiet` (if environment files changed)
- [ ] English and Chinese documentation remain consistent (if applicable)

## Evidence and scope

- [ ] Claims are supported by reproducible evidence or primary references.
- [ ] No credentials, tokens, personal data, or private target details are included.
- [ ] Large binary artifacts are necessary and their provenance is documented.
