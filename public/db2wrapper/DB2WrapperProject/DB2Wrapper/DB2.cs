using IBM.Data.DB2;
using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace DB2Wrapper
{
    public class DB2
    {
        public static string connectionString = "server=10.30.10.90; database=CGS; UID=Admin; password=Zxcv123;";

        private DB2Connection con;
        public string state = "closed";

        public DB2()
        {
            Connect();
        }

        public void Connect()
        {
            con = new DB2Connection(connectionString);

            try
            {
                if (con.State == ConnectionState.Closed) con.Open();
                state = "open";                
            }
            catch
            {
                state = "closed";
            }
        }

        public DataSet Query(string query)
        {
            DataSet ds = new DataSet("root");

            if (!query.Equals(string.Empty))
            {
                DB2DataAdapter adapter = new DB2DataAdapter();
                adapter.SelectCommand = new DB2Command(query, con);
                adapter.Fill(ds);
                con.Close();
                con.Dispose();
            }

            return ds;
        }

    }
}
