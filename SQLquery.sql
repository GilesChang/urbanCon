--Lists all details of countries order by urban concentration--
SELECT CPop.CountryCode, Coun.Name AS Country, CityPopulation, Coun.Population AS CountryPopulation, (CityPopulation / Coun.Population) AS UrbanConcentration
FROM (SELECT CountryCode, sum(Population) AS CityPopulation FROM `city` Group By CountryCode)AS CPop
INNER JOIN `Country` AS Coun on CPop.CountryCode = Coun.code Order by UrbanConcentration Desc

--Create a view to list the total population in cities of a country--
Create View CityPopulation as (SELECT CountryCode, sum(Population) AS CityPopulation FROM `city` Group By CountryCode)

--List the country has max or min urban concentration by "ALL" with the view above--
SELECT CityPop.CountryCode AS Code, CounPop.Name AS Country, CityPop.SumPop as SumCityPop, CounPop.Population AS CountryPop, (CityPop.SumPop / CounPop.Population) AS UrbanConcentration
FROM `CityPopulation` AS CityPop INNER JOIN `Country` AS CounPop on CityPop.CountryCode = CounPop.code 
Where (CityPop.SumPop / CounPop.Population) >= ALL 
(SELECT(CityPop.SumPop / CounPop.Population)
FROM `CityPopulation` AS CityPop INNER JOIN `Country` AS CounPop on CityPop.CountryCode = CounPop.code) 
or (CityPop.SumPop / CounPop.Population)<= ALL
(SELECT(CityPop.SumPop / CounPop.Population)
FROM `CityPopulation` AS CityPop INNER JOIN `Country` AS CounPop on CityPop.CountryCode = CounPop.code)

--Create a view to list not only total population in cities of a country, but also urban concentration of a country--
--Implemented in test--
CREATE View Population AS (
SELECT country.Code AS Code, country.Name as Name, sum(city.Population) AS CityPopulation, country.Population as CountryPopulation, (sum(city.Population)/country.Population) AS UrbanConcentration
FROM `city` INNER JOIN `country` ON country.Code = city.CountryCode GROUP BY Code)

---List the country has max or min urban concentration by aggregation function "Max" and "Min"--
SELECT P.Code, P.Name, P.CityPopulation, P.CountryPopulation, P.UrbanConcentration 
FROM `population` AS P ,(SELECT max(UrbanConcentration) AS Maximum ,min(UrbanConcentration) AS Minimum FROM `population`) AS MaxMin 
WHERE P.UrbanConcentration = MaxMin.Maximum OR P.UrbanConcentration = MaxMin.Minimum

--Lists all details of countries order by urban concentration with Rank and the view "Population"--
--Implemented in test--
SET @rank:= 0
SELECT @rank := @rank + 1, Name, CityPopulation, CountryPopulation, UrbanConcentration 
FROM `population` Order by UrbanConcentration Desc;

--Find out the rank of a specific country--
--Implemented in test--
SET @rank:= 0;
SELECT * FROM (select @rank := @rank + 1 as Rank, Code , Name, CityPopulation, CountryPopulation, UrbanConcentration From`population` Order by UrbanConcentration Desc) as Result WHERE Name = "Taiwan";