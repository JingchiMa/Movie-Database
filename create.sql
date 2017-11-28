USE CS143;
CREATE TABLE Movie (id int not null, 
                    title varchar(100) not null, 
                    year int not null, 
					rating varchar(10), 
					company varchar(50),
	                primary key (id),
					CHECK(year > 0)
					)ENGINE = INNODB;



CREATE TABLE Actor (id int, 
					last varchar(20) not null,
					first varchar(20) not null,
					sex varchar(6),
					dob DATE not null,
					dod DATE,
					PRIMARY KEY (id),
					-- sex has to chosen from 'male', 'female' or 'other'
					CHECK (sex = 'Male' OR sex = 'Female'
					       OR sex = 'Other'),
					-- The date of birth should be earlier than that of death
					CHECK (dob < dod)
                    )ENGINE = INNODB;




CREATE TABLE Sales (mid int,
                    ticketsSold int,
					totalIncome int,
					PRIMARY KEY(mid),
					-- key mid references the id in Movie table
					FOREIGN KEY (mid)
						REFERENCES Movie(id)
						ON DELETE CASCADE,
					-- ticketsSold and totalIncome have to be non-negative
					CHECK (ticketsSold >= 0 AND totalIncome >= 0)
					)ENGINE = INNODB;



CREATE TABLE Director (id int,
					   last varchar(20) not null,
					   first varchar(20) not null,
					   -- Every director must have a dob
					   dob DATE not null,
					   dod DATE,
					   PRIMARY KEY(id),
					   -- The date of birth should be earlier than that of death
					   CHECK (dob < dod)
					   )ENGINE = INNODB;
					   


CREATE TABLE MovieGenre (mid int,
                         genre varchar(20) not null,
						 PRIMARY KEY (mid),
						 -- mid references to the id in Movie table
						 FOREIGN KEY (mid)
						 REFERENCES Movie (id)
							ON DELETE CASCADE					 
)ENGINE = INNODB;



CREATE TABLE MovieDirector (mid int,
                            did int,
							PRIMARY KEY(mid,did),
							-- mid references id in Movie table
							-- The moives in this table should also be in Movie table, too.
							FOREIGN KEY(mid) REFERENCES Movie(id)
								ON DELETE CASCADE,
							-- did refences id in Director table
							-- The directors in this table should also be in Director table, too.
							FOREIGN KEY(did) REFERENCES Director(id)
								ON DELETE CASCADE
)ENGINE = INNODB;




CREATE TABLE MovieActor ( mid int,
                          aid int,
						  role varchar(50),
						  -- mid and aid together is the primary key
						  PRIMARY KEY(mid, aid),
						  -- mid in this table must reference to one id in Movie table
						  FOREIGN KEY(mid) REFERENCES Movie(id)
							ON DELETE CASCADE,
						  -- aid in this table must reference to one id in Actor table
						  FOREIGN KEY(aid) REFERENCES Actor(id)
							ON DELETE CASCADE
)ENGINE = INNODB;




CREATE TABLE MovieRating (mid int,
                          imdb int,
						  rot int,
						  Primary Key (mid),
						  -- mid must reference to id in Movie table
						  FOREIGN KEY (mid) 
							REFERENCES Movie(id)
							ON DELETE CASCADE,
						  CHECK (imdb >= 0 AND imdb <= 100 AND rot >= 0 AND rot <= 100)
)ENGINE = INNODB;



CREATE TABLE Review (name varchar(20),
                     time TIMESTAMP,
					 mid int,
					 rating int,
					 comment varchar(500),
					 -- The person name and the review time together form a primary key
					 PRIMARY KEY(name, time),
					 -- mid in this table must reference to id in Movie table
					 FOREIGN KEY(mid) REFERENCES Movie(id)
						ON DELETE CASCADE,
					 CHECK (rating >= 0 AND rating <= 100)
)ENGINE = INNODB;


CREATE TABLE MaxPersonID (id int,
                          PRIMARY KEY(id)
);


CREATE TABLE MaxMovieID (id int,
                         Primary key (id)
);				 






