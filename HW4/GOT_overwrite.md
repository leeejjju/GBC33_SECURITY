# GOT overwrite

---


dynamically linked 방식(용량줄여줌)으로 컴파일된 파일들은, include한 라이브러리들을 외부에 위치시키고, 함수가 call될 때 내부적으로 plt(procedure linkage table)가 got(global offset table, 라이브러리의 실제 주소)를 참조하도록 해서 연결한다고 함. 


함수 이름 뒤에 @plt가 붙은 친구들은 다 이 방법을 사용한다. 외부 라이브러리 특. 




> [어쩌구주소] <printf@plt>:  jmp     DWORD   PTR ds:[저쩌구주소]


printf함수 내부에서 이런 구문이 있다면, 이게 저쩌구주소, 즉 got로 짬푸하는 과정이다.


이 때 plt가 got로 가야 할 것을 set명령어를 통해 system함수로 가도록 조작해주는 것이 GOT overwrite의 핵심이다. 말 그대로 got를 system함수로 덮어씌워주는(overwrite) 기법. 


system함수는 인자로 받은 명령어를 쉘에서 입력한 것 처럼 작동시켜주는 뭐시깽이라고 한다. 그니까 인자로 /bin/sh 이런거 넣어주면 쉘이 실행되는 것. 


---
## 정리하면!



---

> p system


일케해서 시스템함수 주소를 알아내고...

외부 라이브러리를 사용하는 시점에서 plt주소를 가져와가지구...

> set *[plt주소] = [system함수 주소]


이러면 원래 plt가 가르켜야 할 got주소가 system함수로 바꿔치기됨.


그니까 got로 연결된 거시기 원본 함수에 인자로 들어갈 값이 system 인자로 들어가게 됨


System(어쩌구) 이래하면


어쩌구가 cmd에 입력한 명령어처럼 됨


**굿.**
