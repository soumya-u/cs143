SELECT first, last 
FROM Movie, MovieActor, Actor
WHERE Movie.title = 'Die Another Day' 
AND Movie.id = MovieActor.mid
AND MovieActor.aid = Actor.id;