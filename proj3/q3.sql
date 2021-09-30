SELECT familyName
FROM Person P, Winners W
WHERE P.id = W.id
GROUP BY familyName
HAVING COUNT(*) >= 5;