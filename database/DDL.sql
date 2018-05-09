CREATE TABLE MOVIESBOX.user_(
  username  VARCHAR2(25) primary key,
  password  VARCHAR2(250)        not null,
  first_name      VARCHAR2(30)         not null,
  last_name VARCHAR2(30)         not null,
  email     VARCHAR2(100)        not null
);

CREATE TABLE MOVIESBOX.client (
  username  VARCHAR2(25) primary key references "user"(username),
  join_date DATE default SYSDATE not null
);

CREATE TABLE MOVIESBOX.admin (
    username  VARCHAR2(25) primary key references "user"(username)
);

CREATE TABLE MOVIESBOX.video(
  title VARCHAR2(50) primary key ,
  uploader varchar2(25) not null references "user"(username),
  director varchar2(30) not null ,
  producer varchar2(30) not null ,
  distributer varchar2(30) not null ,
  length number(3) not null ,
  country varchar2(15) not null ,
  language varchar2(10) not null,
  release_date DATE,
  production_date DATE,
  original_title varchar2(100), /*original file name*/
  budget number(10),
  /* press_reviews,
     spectator_reviews
   */
  views number(5) DEFAULT 0,
  rating number(1,1) DEFAULT  0,
  description VARCHAR2(350) not null ,
  quality VARCHAR2(5) not null,

  constraint "quality_ct" CHECK (quality in ('144p','240p','480p','720p','1080p'))
);

CREATE TABLE MOVIESBOX.genre(
  id varchar2(10) primary key ,
  name varchar2(20) not null
);

CREATE TABLE MOVIESBOX.movie_has_genre(
    title VARCHAR2(50) primary key REFERENCES MOVIESBOX.movie(title),
    category varchar2(10) unique REFERENCES MOVIESBOX.genre(id)
);

CREATE TABLE MOVIESBOX.category(
  id varchar2(10) primary key ,
  name varchar2(20) not null,
  main_category varchar2(10) references MOVIESBOX.category(id)
);

CREATE TABLE MOVIESBOX.tv_serie(
    title VARCHAR2(50) primary key REFERENCES MOVIESBOX.video(title),
    episode number(4) not null,
    "session" number(2) not null
);


CREATE TABLE MOVIESBOX.movie(
      title VARCHAR2(50) primary key REFERENCES MOVIESBOX.video(title),
      boxoffice_rank number(2) default null,
      imdb_rating number(1,1) DEFAULT  0.0
);

/*
create TABLE Manga(

)*/



CREATE TABLE privilege(
    id NUMBER(4) primary key ,
    name VARCHAR2(15) not null
);

CREATE TABLE privilege_over_genre(
      privilege NUMBER(4) primary key REFERENCES privilege(id),
      genre varchar2(10) unique REFERENCES genre(id)
);

CREATE TABLE privilege_over_category(
      privilege NUMBER(4) primary key REFERENCES privilege(id),
      category varchar2(10) unique REFERENCES category(id)
);

CREATE TABLE privilege_over_user(
      privilege NUMBER(4) primary key REFERENCES privilege(id),
      username  VARCHAR2(25) unique REFERENCES "user"(username)
);

CREATE TABLE admin_has_privilege(
      id NUMBER(4) primary key REFERENCES privilege(id),
      username  VARCHAR2(25) unique references admin(username)
);