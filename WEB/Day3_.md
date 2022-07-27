# DVWA 모의해킹 사이트?


## 다운로드

[DVWA_window](https://m.blog.naver.com/PostView.nhn?blogId=sjhmc9695&logNo=221303220819&proxyReferer=https:%2F%2Fwww.google.com%2F)

[DVWA_Mac_Linux](http://coashanee5.blogspot.com/2018/07/kali-linux-dvwa-with-xampp.html)

---


## burp suite

프록시를 열어서
중간에 패킷을 가로채서? 나중에 포워딩 할 수 있는? 툴 


[burpSuite](https://portswigger.net/burp/communitydownload)

그 전용 브라우저..?에서 연 링크에 대해?
뭔가 유저가 패킷을 날리면 그게 서버에 가기 전에 가로채서 보여줌. 그걸 맘대로 수정해서 forward로 보낼 수 있는듯. 



## SQL injection

---

* low
> `1' OR 1=1;#` 넣고 submit

---

* medium
> BurpShite의 proxy에서 ID에 `1 OR 1=1` 넣고 forward 

mysqli_real_escape_string()

특수문자같은거를 처리를 해준대 얘가. 그래서 '같은거 쓰면 걸림. low에서 시도한게 안 먹힌단 말이죠. 
특수문자가 들어오면 제거한대.... 


유니온? union을 써서 우회할 수 있다고 함
: 새로운 쿼리를 뒤에 추가시킬 수 있는 뭐시깽이? 

UNION Syntax 서치ㄱㄱ
UNION을 사이에 두고 두개의 쿼리문을 작성 가능? 


> id=1 union select user_id, password from users; &Submit=Submit


이 방식을 low에 적용하면,
> 1' union select user_id, password from users;#



* high


걍 `1' OR 1=1;#` 넣으니까 되는데...? 
LIMIT가 붙어있는게 나름 보안이었다는디 주석처리해주면 될 일임.


* impossible

이건 뚫으라고 해둔게 아니라 이상적인? 예제? 느낌인 듯.  안전하게 한 보안기법은 다음과 같다.


> $data = $db->prepare( 'SELECT first_name, last_name FROM users WHERE user_id = (:id) LIMIT 1;' );

prepare()
: 쿼리문을 예약한다. 딱 이 형식으로만 쿼리가 실행되도록. 나중에 들어올 부분은 비워둘 수 있음.

(:id) 이거가 값이 아니라 들어올 자리.
나중에 bindParam()에서 입력값이 들어감. 이때 char형식으로 인식, 단순 문자열 처리가 되므로 쿼리에 영향을 주지 못하게 됨!! 


---

## XSS (Stored), Stored Cross Site Scripting

게시판에서 악용이 될 수 있다.

게시판에 스크립트 구문을 등록해두면
다른 유저가 그 게시글을 관람했을 때 실행되어버리는 뭐시기.

쿠키 탈취 등의 문제점 유발. 쿠키 속에서 로그인 정보를 포함한 세션정보 등이 탈취된다. 

* low

> <script> alert(document.cookie);</script>

alert는 popup을 띄우는 어쩌구래.
이런 스크립트를 넣어보자 

> security=low; PHPSESSID=5qr77mi4r5qqsnv87hp4402fu9
팝업이 떴다 우와! 

PHPSESSID 이게 세션 아이디. 쿠키 안에 있는 정보. 로그인 유지에 쓰이는 세션... 한 유저에 대한 세션은 안 바뀌도록 되어있나..? 
클릭한 사람의 세션정보다 팝업으로 뜨게 만드는 구문이라고 한다.

해당 메세지를 로드하는 과정에서 스크립트로 실행되기 때문에 

누가 로그인을 하면 세션을 부여받음. 그걸로 로그인 유지.
그 세션이 일정 시간만 지속되는듯. 이후에는 만료되어서 새로 로그인 필요

해당 세션 아이디를 입력해서 로그인된 상태로 뭐시기 가ㅡㄴㅇ.

세션은 서버 내에 저장되므로 불변. 토큰은 클라쪽이라 가변. 대체로 매 로그인에 생성되고 로그아웃에 회수된다고... 

sw개발보안 pdf에서? 자세히 다루나봄... 


암튼 다음레벨 플레이를 위해 저놈은 phpmyadmin에서 삭제해주자. guestbook DB에서 조회/관리 가능

* medium


strip_tags()
이거때매 스크립트 구문 못슴

> $name = str_replace( '<script>', '', $name );
막힘. <script>를 빈 문자열로 치환해버리는... 

> <scr<script> alert(document.cookie);</script>ipt> alert(document.cookie);</script>

이런걸 제목에 써주는거임


* high

> <body onload="alert(document.cookie)">
개발자도구에서 글자수 제한을 풀어준다. 10 -> 50
onload: 사이트에 접속시마다 매번 실행되도록 하는 것. body태그에다가 씀. 

* impossible

-- name에 적용된 것. 

> stripslashes()
: 슬래쉬( \ ) 없애기. 


> mysqli_real_escape_string()
: 특수기호를 Escape string(\n, \r...) 형태로 바꿔줌.


> htmlspecialchars()
: 툭수기호를 html entity(&lt, &amp) 형태로 바꿔줌. 



**굿**