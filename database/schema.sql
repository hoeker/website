
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

CREATE TABLE Position (
  userID int REFERENCES WebUser (userID) ON DELETE Cascade,
  sport text,
  position text,
  skillLevel int,
  PRIMARY KEY (userID, sport, position)
);

CREATE TABLE Rating (
  userID int REFERENCES WebUser (userID) ON DELETE Cascade,
  ratedBy int REFERENCES WebUser (userID) ON DELETE Cascade,
  rating int,
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
  sport text NOT NULL,
  location text,
  date date,
  time time,
  openGame boolean DEFAULT true
);

CREATE TABLE Joined (
  userID int REFERENCES WebUser (userID) ON DELETE CASCADE,
  gameID int REFERENCES WebUser (userID) ON DELETE CASCADE,
  confirmed boolean NOT NULL
);

CREATE TABLE Comment (
  commentID SERIAL PRIMARY KEY,
  gameID int REFERENCES Game (gameID) ON DELETE CASCADE,
  postTime timestamp NOT NULL,
  commentText text NOT NULL
);

