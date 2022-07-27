# DVWA 


## 다운로드

[DVWA_window](https://m.blog.naver.com/PostView.nhn?blogId=sjhmc9695&logNo=221303220819&proxyReferer=https:%2F%2Fwww.google.com%2F)


[DVWA_Mac_Linux](http://coashanee5.blogspot.com/2018/07/kali-linux-dvwa-with-xampp.html)

---


## burp suite

프록시를 열어서 
중간에 패킷을 가로채서? 나중에 포워딩 할 수 있는? 툴 


[burpSuite_Download](https://portswigger.net/burp/communitydownload)


그 전용 브라우저..?에서 연 링크에 대해?
뭔가 유저가 패킷을 날리면 그게 서버에 가기 전에 가로채서 보여줌. 그걸 맘대로 수정해서 forward로 보낼 수 있는듯. 


<br></br>


## SQL injection

---

### low
> ID에 `1' OR 1=1;#` 넣고 submit


* `OR 1=1`을 넣어 무조건 true로 만들고 뒤를 주석처리 해버리는 방법이다.

![SQL_low](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/sql_low.jpg)



<br></br>

---

### medium

* BurpShite를 사용해서 간단하게 뚫기 가능.

> BurpShite의 proxy에서 ID에 `1 OR 1=1` 넣고 forward 



* 근데 `mysqli_real_escape_string()` 이런 애가 적용되어있는데... 
* 특수문자같은거를 처리를 해준대 얘가. 그래서 `'` 같은거 쓰면 걸림. low에서 시도한게 안 먹히는거죠.


* 그래서 우리가 시도해볼 또 다른 방법. `UNION` Syntax을 써서 우회할 수 있다고 함. 
* : 새로운 쿼리를 뒤에 추가시킬 수 있는 뭐시깽이 


> BurpShite의 proxy에서 ID에 `=1 union select user_id, password from users;` 을 넣고 forward.


* 이 방식을 low에 적용하면,


> ID에 `1' union select user_id, password from users;#`을 넣고 submit 


![SQL_mid](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/sql_mid.jpg)


<br></br>

---

### high


* 걍 `1' OR 1=1;#` 넣으니까 되는데...? 
* LIMIT가 붙어있는게 나름 보안이었다는디 주석처리해주면 될 일임.


![SQL_high](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/sql_high.jpg)

<br></br>

---

### impossible

* 이건 뚫으라고 해둔게 아니라 이상적인? 예제? 느낌인 듯.  안전하게 한 보안기법은 다음과 같다.


> $data = $db->prepare( 'SELECT first_name, last_name FROM users WHERE user_id = **(:id)** LIMIT 1;' );

`prepare()`


: 쿼리문을 예약한다. 딱 이 형식으로만 쿼리가 실행되도록. 나중에 들어올 부분은 비워둘 수 있음.

`(:id)` 이거가 값이 아니라 들어올 자리.


나중에 `bindParam()`에서 입력값이 들어감. 이때 char형식으로 인식, 단순 문자열 처리가 되므로 쿼리에 영향을 주지 못하게 됨!! 

<br></br>

---

## XSS (Stored), Stored Cross Site Scripting


* 게시판에서 악용이 될 수 있다.

* 게시판에 스크립트 구문을 등록해두면 다른 유저가 그 게시글을 관람했을 때 실행되어버리는 뭐시기.

* 쿠키 탈취 등의 문제점 유발. 쿠키 속에서 로그인 정보를 포함한 세션정보 등이 탈취된다. 

<br></br>

---

### low

> message에 <script> alert(document.cookie);</script>를 넣고 게시글 등록

![XSSS_low1](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/XSSS_low1.jpg)


* 클릭한 사람의 세션정보다 팝업으로 뜨게 만드는 구문이라고 한다. 이후 누군가 게시글을 클릭하면 해당 메세지를 로드하는 과정에서 스크립트가 실행됨. 
* `alert()`: popup을 띄우는 어쩌구.

> `security=low; PHPSESSID=5qr77mi4r5qqsnv87hp4402fu9`


![XSSS_low2](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/XSSS_low2.jpg)


* 팝업이 떴다 우와! 

* `PHPSESSID` 이게 세션 아이디. 쿠키 안에 있는 정보. 로그인 유지에 쓰이는 세션... 


누가 로그인을 하면 세션을 부여받음. 그걸로 로그인 유지. 그 세션이 일정 시간만 지속되는듯. 이후에는 만료되어서 새로 로그인 필요


세션은 서버 내에 저장되므로 불변. 토큰은 클라쪽이라 가변. 대체로 매 로그인에 생성되고 로그아웃에 회수된다고... 



<br></br>

---

### medium


* `strip_tags()` 이거때매 message에 스크립트 구문 못씀. 

> $name = str_replace( '<script>', '', $name );


* 글고 이걸로도 막힘. <script>를 빈 문자열로 치환해버리는... 

* 이걸 어떻게 뚫느냐??? 


> BurpShite의 proxy에서 name을 `<scr<script> alert(document.cookie);</script>ipt> alert(document.cookie);</script>`로 바꿔준다.


![XSSS_mid](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/XSSS_mid.jpg)


<br></br>

---

### high


* 일단 개발자도구에서 글자수 제한을 풀어준다. 10 -> 50


![XSSS_high1](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/XSSS_high1.jpg)



> 제목에 <body onload="alert(document.cookie)"> 일케 해주자


![XSSS_high2](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/XSSS_high2.jpg)
* onload: 사이트에 접속시마다 매번 실행되도록 하는 것. body태그에다가 씀. 


쨘.
![XSSS_high3](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/XSSS_high3.jpg)


<br></br>

---

### impossible

* name에 적용된 것. 

> stripslashes()
: 슬래쉬( \ ) 없애기. 


> mysqli_real_escape_string()
: 특수기호를 Escape string(\n, \r...) 형태로 바꿔줌.


> htmlspecialchars()
: 툭수기호를 html entity(&lt, &amp) 형태로 바꿔줌. 



**굿**