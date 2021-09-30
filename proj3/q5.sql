SELECT COUNT(DISTINCT A.awardYear)
FROM Winners A, Organization O
WHERE A.id=O.id;