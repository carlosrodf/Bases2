use proyecto;
SELECT table_name AS TABLA,table_collation AS COLLATION,ROUND(DATA_FREE/(DATA_LENGTH+INDEX_LENGTH)) AS FRAGMENTACION
FROM information_schema.tables
WHERE table_schema = 'proyecto';