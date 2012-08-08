CREATE TABLE WebUser (
  userID SERIAL PRIMARY KEY,
  userName text UNIQUE,
  password text NOT NULL,
  firstName text,
  lastName text,
  phoneNumber text,
  emailAddress text NOT NULL,
  location text,
  active boolean DEFAULT true,
  admin boolean DEFAULT false
);

CREATE TABLE Sport (
  sportID SERIAL PRIMARY KEY,
  sportName text
);

CREATE TABLE Position (
  positionID SERIAL PRIMARY KEY,
  sportID int REFERENCES Sport (sportID) ON DELETE CASCADE,
  positionName text
);

CREATE TABLE Plays (
  userID int REFERENCES WebUser (userID) ON DELETE CASCADE,
  positionID int REFERENCES Position (positionID) ON DELETE CASCADE,
  skillLevel int,
  PRIMARY KEY (userID, positionID)
);

CREATE TABLE Rating (
  userID int REFERENCES WebUser (userID) ON DELETE CASCADE,
  ratedBy int REFERENCES WebUser (userID) ON DELETE CASCADE,
  rating int NOT NULL DEFAULT 0,
  PRIMARY KEY (userID, ratedBy)
);

CREATE TABLE Friend (
  userID1 int REFERENCES WebUser (userID) ON DELETE CASCADE,
  userID2 int REFERENCES WebUser (userID) ON DELETE CASCADE,
  PRIMARY KEY (userID1, userID2),
  CHECK (userID1 < userID2)
);

CREATE TABLE Game (
  gameID SERIAL PRIMARY KEY,
  organiserID int REFERENCES WebUser (userID) ON DELETE SET NULL,
  sportID int REFERENCES Sport (sportID) ON DELETE SET NULL,
  location text,
  date date,
  time time,
  privacy text CHECK (privacy IN ('private', 'invite', 'public')),
  description text
);

CREATE TABLE Joined (
  userID int REFERENCES WebUser (userID) ON DELETE CASCADE,
  gameID int REFERENCES Game (gameID) ON DELETE CASCADE,
  confirmed boolean NOT NULL DEFAULT false
);

CREATE TABLE Comment (
  commentID SERIAL PRIMARY KEY,
  gameID int REFERENCES Game (gameID) ON DELETE CASCADE,
  userID int REFERENCES WebUser (userID) ON DELETE SET NULL,
  postTime timestamp NOT NULL,
  commentText text NOT NULL
);


-- Types

CREATE TYPE searchCriteria AS (
  sportID int,
  startDate date,
  endDate date,
  location text
);

CREATE TYPE searchResult AS (
  gameID int,
  resultRank bigint,
  totalResults bigint
);
