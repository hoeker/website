
CREATE OR REPLACE FUNCTION search(
    criteria searchCriteria,
    activeUserId int,
    numResults int,
    offs int
)
RETURNS setof searchResult
AS $body$

  WITH gameScores AS (
  SELECT
      g.gameID,
      sum(r.rating) AS totalRating,
      sum(CASE WHEN r.ratedBy=$2 THEN r.rating END) AS myRating,
      date
  FROM Game AS g
  LEFT OUTER JOIN Joined AS j
      ON j.gameId=g.gameId
  LEFT OUTER JOIN Rating AS r
      ON r.userId=j.userId
  WHERE (
      sportID=$1.sportID
      OR (
          $1.sportID IS NULL
          AND (
              sportID IN (SELECT sportID FROM Plays WHERE userID=$2)
              OR NOT EXISTS (SELECT sportID FROM Plays WHERE userID=$2)
              )
          )
      )
      AND coalesce($1.startDate, date)<=date
      AND date<=coalesce($1.startDate, date)
      AND location ILIKE '%' || coalesce($1.location, '') || '%'
  GROUP BY g.gameID, date
  ),

  gamesRanked AS (
  SELECT
      gameID,
      totalRating+myRating AS gameScore,
      date,
      row_number() OVER
          (ORDER BY totalRating+myRating DESC, date, gameID)
          AS resultRank,
      count(*) OVER () AS totalResults
  FROM gameScores
  )
  
  SELECT gameID, resultRank, totalResults
  FROM gamesRanked
  ORDER BY gameScore DESC, date, gameID
  LIMIT $3
  OFFSET $4;

$body$
LANGUAGE 'sql';
