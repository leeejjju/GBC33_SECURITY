# crackme


입력한 비밀번호가 맞는지 대조하는 부분에서 맞는 password를 알아낸다. 
입력 형식에 맞게 변환하여 보는 것이 포인트. 



대조하는 부분을 파봐야하므로, cmp함수에 주목한다. 비교하는 두 인자를 확인하기. 주소가 들어있다면 

> p $[주소] 

해서 속의 값을 읽어내면 된다. 

> p/d [값] 

하면 10진수로 간편 변환 가능.



또는 두 값을 인자로 주어 함수를 call하는 과정에 주목. 들어가는 인자들을 살펴보면 하나는 입력값이고 하나는 password일 것. 



---

# BOF



일단 c파일을 뜯어봐야 뭘 해야할지 알게 된다. 


## 1~4: 


---
    innocent라는 변수를 원하는 값으로 덮어씌우기. 
	선언순서상 innocent가 먼저, buf가 나중에이므로… 내부 stack의 구조는 다음과 같을 것이다.


---

> (High address)


> RET


> SFP


> Innocent


> Buf


> (Low address) 



---

여기서 우리가 Buf에 값을 넘치게 입력하면 아마 위에 innocent까지 침범할 거임. 
그러니 innocent에 원하는 값을 딱 덮어씌우기 위해서는 buf의 시작점~innocent의 시작점 거리의 정확한 값을 알아내 그만큼 dummy값으로 채우고, 이어서 innocent에 씌울 값을 넣어줘야한다.



Innocent가 다루어지는 부분인 cmp, buf가 할당되는 과정인 gets에서 각각의 주소를 얻어낸다.
Buf는 gets에서 rdi가 변하는 그거인데 왜인지는 사실 모르겠음. Rdi가 뭐지…? 달고나 봐야할 듯



두 주소값을 빼면… high에서 low를 빼야 양수가 나오니까 먼저 선언된거에서 나중에 선언된거를 빼면은. Innocent – buf, 거리가 나온다.


그만큼의 dummy + innocent를 덮을 값을 인자로 넣어주면 됨. 


표준입력값처럼 넣어줄 때 꿀팁. python구문 뒤에 cat을 넣어주고 괄호( )로 묶어줘야 입력 스트림 끝났다고 안 끝나고 계속 따낸 쉘을 사용할 수 있다구 한다. 파이프( | )로 실행명령과 연결해주면 됨. Argument로 넣고싶다면 아래 구문으로. 


>	(python -c “print ‘x’*[distance] + [KEY]”;cat) | ./bof


>	./bof ` python -c “print ‘x’*[distance]  + [KEY]”`


만약 입력값을 hex상태 고대로 넣고 싶다면, little endian으로 변환해서 써주면 된다.

`0x12345678 >> \x78\x56\x34\x12`


---
## 5 


Innocent를 덮음과 동시에 buf값에 유의미한 뭐시기를 넣고싶다. 예를 들면 “/bin/sh” 이런거
동일하게 하되, 


Buf의 앞쪽 값을 ‘/bin/sh\x00’ 이걸로 넣어주고, 넣어준 크기를 뺀 만큼… 그니까 다 더해서 원하는 distance가 될만큼의 dummy값을 붙여주면 된다. 


>	(python -c “print ‘/bin/sh\x00’ + ‘x’*[distance-8] + [KEY]”;cat) | ./bof

---
## 6


이번에는 main함수의 return address를 shellcode로 바꿔치기해서 프로그램이 끝나는 시점에 쉘이 열리도록 해보자. 원리는 동일하다. 스택의 내부 구조는 이럴 것이고…


---
> (high address)


> RET


> SFP


> Buf


> Shellcode


> (low address)
---

하고싶은건 buf에 값을 잔뜩 넣어 위에까지 침범해 덮게 하되, 딱 RET부분을 shellcode 주소로 덮는 것이다.


그러니 buf~RET까지의 크기를 알아야 하는데… buf가 가장 먼저 선언되어있으므로, 게다가 딱 8의 배수인 128이므로 buf와 SFP사이의 dummy는 없다고 간주하고 그 거리를 128로 둬도 될 듯. 

그러면 거기서 SFP까지 덮도록… 64bit운영체제이므로 8byte만큼을 더하여 132만큼의 dummy와 RET를 덮을 shellcode 주소값을 buf로 넣어주면 되겠다. 



>	(python -c “print ‘x’*[buf size + SFP size] + [shellcode address]”;cat) | ./bof


**굿**



