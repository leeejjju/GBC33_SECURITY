# RTL (Return To Libc)

---




스택에서 쉘을 실행시키지 못하도록 하는 보안기법(NX bit, Never Execute bit)을 우회하는 방법.


핵심은 함수의 RET 부분에 라이브러리 함수를 덮어씌워 함수 종료 시점에 그것이 실행되도록 하는 것. 주로 사용되는 것은 물론 우리의 system 함수이다. 


system함수의 주소는 이렇게 찾아낼 수 있다.


> p system



**


작동 방식을 조금 더 들여다보자면, 함수의 에필로그 과정인 leave와 ret에서 일어나는 사건을 확인할 필요가 있다.


- leave (move esp, ebp ; pop ebp) -> 쌓여있던 지역변수들 공간 반환, ebp에는 이전 함수의 ebp가 pop되어 들어옴. 
- ret (pop eip; jmp eip) -> eip에 pop된 주소를 저장하고 거기로 짬푸한다,


이 때, 우리가 할 일은 BOF를 일으켜 system함수 주소를 ret에 넣어주는 것. 그럼 ret 이후 기존 함수가 종료되는 것이 아니라 새로운 함수(system)를 실행시키게 된다.


생각해보아야 할 것은 함수 인자를 넣는 방법. 쉘 실행을 위한 '/bin/sh'를 system함수에 넣어주고싶을 때... 


RET의 한 단계 전에 위치한 args 영역에다가, 라이브러리에 이미 존재하는 '/bin/sh'의 주소를 넣어주면 된다고 한다. 


그 주소는 다음과 같이 알아낸다.


> find &system, +99999999, "/bin/sh"


그러므로 우리가 작성해야 할 페이로드는 다음과 같겠다. 



> `python -c "print 'x'*[적절한 크기] + [system 함수 주소] + 'xxxx' + ['/bin/sh' 주소] "`


( 중간의 xxxx는 새로 호출된 함수의 RET영역을 덮기 위한 dummy. 이걸 지나야 args 영역이 나오기 때문! )




**굿.**

