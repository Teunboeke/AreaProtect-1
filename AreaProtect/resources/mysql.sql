CREATE TABLE IF NOT EXISTS areaprotect_areas (
  owner VARCHAR(16) PRIMARY KEY,
  x1 INT,
  y1 INT,
  z1 INT,
  x2 INT,
  y2 INT,
  z2 INT,
  pvp TINYINT(1) DEFAULT null,
  build TINYINT(1) DEFAULT null,
  destroy TINYINT(1) DEFAULT null
);
