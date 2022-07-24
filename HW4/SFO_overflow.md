# SFP overflow


---


*stack의 구조를 보자.*



> RET


> SFP


> buf


일케 되어있다 쳤을 때...

buf의 제일 윗부분, 저 거꾸로된 stack상에서는 제일 꼬랑지에 buf의 위치를 나타내는 주소가 붙어있다고 함. 이걸 이용해볼거에요.


**BOF를 일으켜 SFP에 buf의 주소값 + 두칸(32bit 기준 8byte)의 주소를 집어넣는겁니다.**

SFP가 ebp를 가리키는 포인터자나요. 그니까 이렇게 해주면은 원래 함수의 종료 시점에서... 




### leave(mov esp ebp, pop ebp) 


할 때 

esp에 ebp를 넣어주고, ebp에는 stack에서 pop된, 그니까 그 아래거를 넣어줄텐데 그게 SFP고, 우리가 덮어씌운 [ buf 주소의 윗칸 ]인거임. 그리고...




ㄴ


### ret(pop eip, jmp eip)

되면... 


거기서 또 pop되서 나오는 그 하나 아랫칸이 가리키는 곳으로 짬푸하게 될텐데, 그 아랫칸이 [ buf의 주소 부분 ]이니까 buf로 짬푸하는거임. 



*이 때 buf 값으로 shellcode를 넣어두면 쉘을 따낼 수 있다. 와 ! !*


***
***


예를 들어 


> ./sfp_overflow `python -c "print [shellcode] + '\x00' + [buf크기-shellcode크기-1 만큼의 dummy] + [buf 주소-8(32bit일때)의 마지막 한 byte]"`


(buf 주소-8은 leave시점에서 p $esp-8 하면 바로 나옴!)


