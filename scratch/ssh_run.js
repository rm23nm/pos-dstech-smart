const { Client } = require('ssh2');
const conn = new Client();
const command = process.argv[2] || 'ls -la /www/wwwroot/pos.dstechsmart.com';

console.log('Connecting to SSH server...');
conn.on('ready', () => {
  console.log('SSH connection established successfully!');
  console.log('Executing command:', command);
  conn.exec(command, (err, stream) => {
    if (err) {
      console.error('Execution error:', err);
      conn.end();
      return;
    }
    stream.on('close', (code, signal) => {
      console.log(`Command exited with code: ${code}`);
      conn.end();
    }).on('data', (data) => {
      process.stdout.write(data);
    }).stderr.on('data', (data) => {
      process.stderr.write(data);
    });
  });
}).on('error', (err) => {
  console.error('SSH Connection error:', err);
}).connect({
  host: '157.66.34.199',
  port: 11058,
  username: 'root',
  password: 'M4m4cantik@dstechsmart10051987HdqHq345'
});
