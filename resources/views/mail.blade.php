<body style="margin: 0; padding-top: 100px; padding-bottom: 50px; background: #f7f7f7; font-family: sans-serif;">
    <div style="background: #fff; padding: 20px 10px; width: 80%; z-index: 5; margin: auto; border: 1px solid rgba(0,0,0,.1)">
      <table style="background: #fff; margin: auto;" width="80%">
        <tr>
          <td>
            <h2 style="font-family: sans-serif;">嗨，管理者</h2></td>
          <td>
            <p style="color: #777; text-align: right;">來自104 Fashion Store 的使用者</p>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <h3>我的名字是 {{ $name }}</h3>
            <br />
            <table>
              <tr>
                <td width="60%">Email address</td>
                <td>{{ $email }}</td>
              </tr>
              <tr>
                <td>電話</td>
                <td>{{ $phone }}</td>
              </tr>
              <tr>
                  <td>問題與建議</td>
                  <td>{{ $subject }}</td>
              </tr>
              <tr>
                  <td>詳細內容</td>
                  <td>{{ $msg }}</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </body>