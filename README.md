
<h1 align="center">StudyApp</h1>

# １．サービス概要/URL
## サービス内容
<p style="text-indent:1em;">
タスク管理、ブックマークマネージャーといった業務効率化アプリとなります。業務効率化アプリのため、高速なページ遷移を実現するため、SPAにて開発しております。</p>
<p style="text-indent:1em;">
現在の主な機能はタスク管理、ブックマークマネージャー（技術記事整理）の２点となります。
</p>
<p style="text-indent:1em;">
加えて、仮会員登録（メール認証）、本登録、お問い合わせ等の基本機能を備えています。
</p>
<p style="text-indent:1em;">
エンジニアの日々の業務の助けとなるようなお助け機能を今後も追加開発していきます。
</p>

## 作成目的
- プログラミングのアウトプット学習を行うため
- 自分好みの業務用アプリ（ツール）を作成したかったため
- 無料で自分好みの業務アプリを使用したかったため(Notion等の有料アプリは有料)

## アプリURL
### https://muscle-myapp.com/login <br>
<p style="text-indent:1em;">※ Basic認証を設定しています<br>
　　ID => hanyu <br>
　　PW => y@y@ <br>
</p>
<p style="text-indent:1em;">※ 現在は自身以外の利用を想定していないため、Basic認証を設定しています。</p>
<p style="text-indent:1em;">※ お試し利用はログイン画面、右下にございますゲストログインからお試し利用をお勧めします。（会員登録不要です）</p>

![ゲストログイン](/public/img/guestLogin.png)
<br>

# ２．AWS(インフラ)構成図
![インフラ構成図](/public/img/infraStractrure.png)
※ 個人利用のため冗長化構成等は行なっていません。

# ３．使用技術
|No|技術名等|採用経緯|
|:---|:---|:---|
|1|PHP(ver8.1)|実務で使用しているため|
|2|laravel(ver8.8)|実務で使用しているため|
|3|MySQL(ver8.0)|実務で使用しているため|
|4|Nginx(ver1.20)|実務で使用しているため|
|5|MySQL(ver8.0)|実務で使用しているため|
|6|docker|ローカル環境のため|
|7|TypeScript(ver4.5)|バグ未然防止を優先し、JSではなくTSを採用。実務で使用しているため|
|8|Vue.js(ver2.6)|SPA開発のため（実務ではver3ではなくver2.6のため、あえてverは2系を採用）|
|9|vuex(ver3.6)|state管理を行う機能が発生したため|
|10|vuex-persistedstate(ver4.1)|state情報をweb-strageに保存する必要があるため|
|11|vue-router(ver3.5)|フロントのルーティングで使用|
|12|vuetify(ver2.6)|CSS工数削減のため Vue.jsと相性が良いため|
|13|vuedraggable(ver2.2)|タスク管理機能でドラック&ドロップ機能を使用するため|
|14|vue-class-component(ver7.6)|TS採用により、こちらも導入|
|15|vue-property-decorator(ver9.1)|TS採用により、こちらも導入|

# ４．機能一覧
## 仮会員登録→本会員登録機能
<p style="text-indent:1em;">ECサイト開発で実装されるメール認証を用いた仮会員登録機能を実装しています。
メール登録後、登録メールへtoken付きURLを送付し、当該URLから本会員登録をして頂く、という仕様としています。</p>

![仮会員登録](/public/img/karikaiinn.png)

## タスク管理
<p style="text-indent:1em;">良くありがちなタスク管理です。直感的に使用できるUI /UXとしたく、リスト形式ではなく、カード羅列形式のタスク管理です（私の好み）。</p>
<p style="text-indent:1em;">CRUD機能は一通り付いています。タスクはドラッグ&ドロップで順番入れ替え可能です。入れ替えた順番は保存されるため、ブラウザリフレッシュ時も順番は保持されます。</p>

![タスク管理](/public/img/todo.png)

## ブックマーク管理
<p style="text-indent:1em;">
googleブラウザ標準のブックマークよりも使用しやすいブックマークマネージャを作成するため、実装した機能となります。
よく使用するブックマークは上部へFavicon画像付きで保存することができ、ワンクリックで別タブでページを表示します。
</p>
ブックマークフォルダ、ブックマークに関するCRUD機能は一通り付いており、かつフォルダごとに色をつけれますので、視認性を工夫しています。m、

![タスク管理](/public/img/bookmark.png)

<p style="text-indent:1em;">googleブックマークか</p>


