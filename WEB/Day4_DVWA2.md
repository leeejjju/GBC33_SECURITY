# DVWA 02




> `http://localhost/dvwa/security.php`


> `http://localhost/phpmyadmin/`



# Command Injection



ip를 치면 ping을 때린 결과가 나오는 뭐시깽이가 있다고 하자. 


내부적인 동작 원리는 유저의 입력이 ping 커멘드 뒤에 들어가게 되는건데... 
그니까 서버에서 리눅스 명령이 먹는건데, 

그 환경에서 다른 원하는 명령을 실행시킬 수 있도록 하는것이 command injection.  



<br></br>
---

## low


리눅스 명령에 앞에 명령을 실행하고 뒤에명령을 또 실행하는 명령어!

 `&&`로도 할 수 있고, `;`으로도 할 수 있고. 예를 들어... 


> `whoami; pwd`


> `whoami && pwd` (둘 다 true일 때 뜨나...? 뒤가 틀리면 앞만 실행되고 앞이 틀리면 아예 실행 안되고 )



그래서 어떻게 해보느냐! 


> 입력란에 `127.0.0.1 && dir` 입력

![CI_low](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/CI_low.jpg)

window환경이라 글자는 깨졌지만 제대로 dir명령의 결과가 출력된 것을 알 수 있다. 



<br></br>
---


## medium

![CI_mid0](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/CI_mid0.jpg)


* `&&`와 `;`을 replace()을 이용해 막아둔 것을 확인할 수 있다. 사라지게 됨. 



이럴 때 꼼수. 이 친구들은 먹힐 것이다. 


> `127.0.0.1 &;&; dir`
* `;`가 대체되어 사라지면 온전한 `&&`가 남는다. 


> `127.0.0.1 || ls`
* 얘도 둘다 true니까 동일하게 작동할텐데 밴이 안 되어있음 개꿀.




![CI_mid](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/CI_mid.jpg)


굿.

<br></br>
---

## high



![CI_high0](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/CI_high0.jpg)


블랙리스트가 더 치밀해졌다. 그러나?


replace 대상이 `'|'`가 아닌 `'| '`이었던 것이 구멍. 이걸 공략한다 


> 입력란에 `127.0.0.1 |&|& dir` 입력. 

![CI_high](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/CI_high.jpg)


굿. 


<br></br>
---

## impossible


그렇다면 command injection의 이상적인 보안 형태는 어떨까.


그냥 medium에서 구멍이 지워지면 된다. 


그리고 `explode()`, `is_numeric()`을 사용해서 유효한 IP입력값인지 확인 과정을 거쳐줌.


굿.



# File Upload

<br></br>
---

## low


![File_Upload_low1](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/FU_low1.jpg)

이 소스코드가 작성된 php파일을


![File_Upload_low2](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/FU_low2.jpg)


![File_Upload_low3](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/FU_low3.jpg)

업로드해주면 경로가 뜬다


> [아까 알아낸 경로]/[업로드한 php파일명]?cmd=[원하는 커멘드]


이 형식을 브라우저 주소창에 입력함으로서 서버의 터미널처럼 사용 가능.
![File_Upload_low4](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/FU_low4.jpg)



리다이렉션 `>`를 이용하여 원하는 소스코드 파일을 업로드 가능. 


> `echo "[원하는 소스코드]" > newfile.php`


웹쉘(webshell)이라고 불린다. 이렇게 해서 서버가 뚫리면 골로 간다고 보면 된대 


<br></br>
---

## medium

![File_Upload_mid0](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/FU_mid0.jpg)


나름 막아둠 이미지가 아니면 안 되도록


하지만 허술하다. low에서와 동일한 php파일을 업로드 시도하되, proxy를 이용하여 뭔가 바꿔 forward해보자. 


* MIME타입: 전송된 문서의 타입을 알려주는 매커니즘. 문서 타입 정보를 실어나르는 유일한 정보는 아니다. 


> 업로드할 때 proxy에서 `contents-Type`을 `image/png`로 바꿔주고 forward.


![File_Upload_mid1](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/FU_mid1.jpg)


![File_Upload_mid2](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/FU_mid2.jpg)


짜잔티비. 잘 업로드 되었다.
![File_Upload_mid3](https://github.com/leeejjju/GBC33_SECURITY/blob/main/img/FU_mid3.jpg)


이제 경로를 알아냈고 업로드가 잘 되었으니 아까와 같은 방식으로 웹쉘 실행 가능! 


<br></br>
---

## high

이거 하려면 File Inclusion을 알아야해서 일단 skip


**굿**