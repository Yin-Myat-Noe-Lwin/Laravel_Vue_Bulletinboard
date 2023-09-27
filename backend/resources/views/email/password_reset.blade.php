<h1>Welcome to Bulletin_Board, {{ $mailData['userName'] }} </h1>
  <p>Your username is {{ $mailData['userName'] }}.</p>
  <a href="http://localhost:5173/resetPassword/{{ $mailData['userId'] }}-{{ $mailData['token'] }}">http://localhost:3000/password_resets/{{ $mailData['token'] }}/edit</a>
  <p>Click the link and reset password.</p>
  <p>Thanks for joining and have a great day!</p>
