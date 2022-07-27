# DVWA 모의해킹 사이트?

[DVWA_window](https://m.blog.naver.com/PostView.nhn?blogId=sjhmc9695&logNo=221303220819&proxyReferer=https:%2F%2Fwww.google.com%2F)

[DVWA_Mac_Linux](http://coashanee5.blogspot.com/2018/07/kali-linux-dvwa-with-xampp.html)

---

# mysql 조금 더

> CREATE DATABASE gbc;


> USE gbc;


> CREATE TABLE users( 
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id VARCHAR(20) NOT NULL,
    user_pw VARCHAR(20) NOT NULL,
    age INT NOT NULL,
    rc VARCHAR(20) NOT NULL);


> CREATE TABLE dorm(
    name VARCHAR(20) PRIMARY KEY,
    num INT NOT NULL,
    num_of_rooms INT NOT NULL,
    num_of_students INT NOT NULL);

* 잔뜩 삽입

> INSERT INTO users VALUES(NULL, 'user1', 'user1', 23, 'rodem');


> INSERT INTO users VALUES(NULL, 'user2', 'user2', 21, 'creation');


> INSERT INTO users VALUES(NULL, 'user3', 'user3', 20, 'happy');


> INSERT INTO users VALUES(NULL, 'user4', 'user4', 25, 'carmichael');


> INSERT INTO users VALUES(NULL, 'user5', 'user5', 21, 'vision');


> INSERT INTO users VALUES(NULL, 'user6', 'user6', 22, 'rothem');


INSERT INTO dorm VALUES('rodem', 202, 552, 1020);
INSERT INTO dorm VALUES('creation', 203, 400, 1020);
INSERT INTO dorm VALUES('carmichael', 204, 208, 1020);
INSERT INTO dorm VALUES('happy', 205, 500, 1020);
INSERT INTO dorm VALUES('vision', 205, 308, 1020);



## 검색조건 설정


* id기준 내림차순 정렬하여 보기

> SELECT * FROM users ORDER BY id DESC;

* 내림차순

> SELECT * FROM users ORDER BY id ASC;



* 로뎀 인간들만 보기

> SELECT * FROM users WHERE rc='rodem'



* 로뎀 혹은 창조관 인간들 보기

> SELECT * FROM users WHERE rc IN ('rodem', 'creation');

* 두 테이블의 cross join..? 테이블끼리 크로스오버 해서 모든 조합을 조회. 
> SELECT * FROM dorm, users;


* 괄호로 묶인 SELECT문이 리턴된 결과값으로 취급되어 다른 구문에 사용될 수 있다는 뭐시기입니다.
> SELECT * FROM users WHERE rc IN (SELECT name FROM dorm WHERE name='rodem');


### LIKE

* 검색어....? 
> SELECT * FROM dorm WHERE name LIKE '%o%';

%는 뭐가 오든 상관 없다는 뜻. 그니까 중간에 o가 있으면 서치에 걸리는거임. 
o로 끝나는거는 %o, o로 시작하는거는 o%로 조회 가능. 


### LIMIT, 갯수제한

* 앞에서부터 세개만 보여주기
> SELECT * FROM users LIMIT 3;

* 다른거랑 혼용시 LIMIT은 제일 끝에 써주자
> SELECT * FROM users ORDER BY DESC LIMIT 3; dhfsdfasdfasdfasdf오류뜸 왜지?


### max, min

* 제일 큰 값 가져옴
> SELECT max(id) FROM users;

### COUNT

> SELECT COUNT(*) FROM users;
> SELECT COUNT([필드명]) FROM users;

필드에 있는 데이터의 갯수(row갯수). null은 제외된대. 

### DELETE

DELETE FROM users; #users테이블에 있는 모든 데이터 삭제
DELETE FROM users WHERE user_id='user2'; #user2의 데이터 삭제

### UPDATE

* user_id가 user3인 사용자의 user_id를 user33으로 변경
> UPDATE users SET user_id='user33' where user_id='user3';
