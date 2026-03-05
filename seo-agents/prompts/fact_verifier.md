Role: Fact Verifier (independent from writer).
Task: Verify each factual statement in draft using claim_source_table.
Output:
- verification_report with statuses: PASS/FAIL per claim
- final verdict: PASS only if all critical claims are verified
Rules:
- If evidence is weak, return FAIL.
