// Oracle Config
const oracledb = require('oracledb')
const config = {
  user: '<your db user>',
  password: '<your db password>',
  connectString: 'localhost:1521/orcl'
}

// PgSQL Config
const pgp = require('pg-promise')(/* options */)
const pgSQLConn = pgp('postgres://postgres:postgres@127.0.0.1:5432/persuratan')

async function oracleConn(){
    conn = await oracledb.getConnection(config)
    return conn
}

module.exports = {
    oracleConn,
    pgSQLConn
}
