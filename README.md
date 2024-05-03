# php(codeigniter4)와 node.js(채팅서버)를 이용한 커뮤니티 사이트 입니다.
- 24.04.15 ~ 업데이트 중

# 실행방법
- 로컬 서버 시작 시 cd community로 이동하여 php spark serve 를 입력하여 실행

- 채팅 서버 시작을 위해서는 cd community-ws로 이동하여 yarn debug를 하여 채팅 서버 활성화

- 기본 테이블 정보는 database.sql 안에 있습니다.

- 테이블 init은 init.sql에 있습니다.

# 사용 스택
- php, codeigniter4, javascript, jQuery, bootStrap, aws-sdk(aws s3를 이용하기 위한), 스마트에디터2 (게시글 작성, 수정 편리를 위해 사용)
- socket.io (채팅서버를 위해 사용), mysql

# 주요 기능
- 로그인 시 세션을 통해 유저 정보를 저장
- 로그인 후 채팅을 이용한 유저들과 소통
- 이미지 게시글, 일반 게시글 작성, 수정, 삭제
- 댓글 입력
