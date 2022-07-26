>> mysql의 대부분 문법들은 case sensitive하지 않은 듯. 심지어 조건절로 필드 내 데이터를 대조할 때 조차...  근데 쿼리문은 다 대문자로 작성하는게 국룰이라고 한당. 

---



## 데이터베이스 조회
>	show databases;

* 여기에 조회된 하나하나가 database.
* Information schema -> 가장중요함 모든 정보가 있ㅇㅁ.해커가 탐냄. 


## 데이터베이스 만들기
>	CREATE DATABASE [database name];

* 거기로 이동
>	Use [database name];


## 테이블 만들기

> CREATE TABLE ([tableName]([fieldName] [fieldType] (PRIMARY KEY) (AUTO_INCREMENT), [tableName]([fieldName] [fieldType] (NOT NULL), ...... );



> CREATE TABLE USER(id INT PRIMARY KEY AUTO_INCREMENT, user_id VARCHAR(20) NOT NULL, user_pw VARCHAR(20) NOT NULL, age INT NOT NULL);
* PRIMARY KEY -> not null이면서 unique한 속성을 가짐. 테이블 안에서 오직 한 개만 존재. 
* AUTO_INCRESMENT -> 뭐 자동으로 증가되는거겠지...? 
* NOT NULL -> 해당 필드를 빈 칸으로 둘 수 없도록 하기
* 실제로는 pw를 암호화해서 hash값으로 저장함

## 테이블의 타입들?필드? 정보 보는 명령어

> desc [tableName];


> desc USER;

## 테이블 엎어버리기(지우기)

> drop table [tableName];


> drop table USER;

## 데이터 넣기

> INSERT INTO USER VALUES (NULL, 'JJ', 'jj', 21);

* value들은 field가 선언된 순서대로 넣는게 국룰. 다른 순서로 넣고싶다면 앞에 필드들을 원하는 순서대로 정렬한거를 하나 추가하면 된다는데...

> INSERT INTO USER VALUES (id, age, user_id, user_pw) (NULL, 21, 'JJ', 'jj');

* 굳이...? 


## 데이터 조회


> SELECT [filed name] FROM [table name] WHERE [fieldName]=[value];

* 전체 조회
> SELECT * FROM USER;


* 필터링!
> SELECT * FROM USER WHERE age=21;


* 사용자 확인, 로그인의 아주아주 간단한 쿼리문 
> SELECT * FROM USER WHERE user_id='Kim' AND user_pw='Kim';



# 


유저가 입력해서 서브밋하면... 
그럼 입력한 데이터가 args로 넘어가서?
패킷 안에 데이터로 보내져서? (get이면 표시하면서 port면 숨기면서) 
그럼 웹서버가 그걸 받아서 php에 떤져
그럼 $post로 받아서 쿼리 구문에 넣는...? 


$_POST["keyword"] 이게 패킷으로 들어온?데이터? 


> SELECT * FROM USER WHERE user_id=$_POST["id"] AND user_pw=$_POST["pw"];


이런식으로 사용하나봄. 


### 여기서 문제가 발생할 여지가 있는데..!!

만약 유저가.... 

`'user3' OR 1=1;# `


이거를 ID로 넣어버리면...? 앞에서 TRUE로 판정나버리고 뒤가 다 주석처리 되어버림. 
> SELECT * FROM USER WHERE user_id='user3' OR 1=1; *#  AND user_pw=$_POST["pw"];*

그니까 pw입력 없이 id만으로 로그인이 가능해져버림. ㄴㅇㄱ 

아니면 pw에다가 

`' OR 1=1';#`

> SELECT * FROM USER WHERE user_id='user3' AND user_pw='' OR 1=1'; *#';*

이르케 쿼리문을 조작하는 방식이 **SQL injection** 이라구 한다.

#
#
#



글고 이게뭐냐면... 테스트용으로 php들 만들었을때 거시기 로컬호스트로 서버 실행시키는..?거인듯?

> php -S localhost:8080 index.php


그리고 웹 브라우저에서 `locathost:8080` 치면 거기에 머시기 그거 뜸. 




