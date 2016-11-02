using System;
using System.Collections.Generic;
using System.Configuration;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;

namespace DB2Wrapper
{
    class Program
    {
        static void Main(string[] args)
        {
            /*
            string query = "select * from CGS.\"ITEM_INFO\" where ITEM_KEY = 1482847 limit 1";
            DB2 sql = new DB2();
            Console.WriteLine("========================================================");
            Console.WriteLine("Executing: " + query);
            DataSet ds = sql.Query(query);

            string debugQuery = ExcecuteDebug("select * from CGS.\"ITEM_INFO\" where ITEM_KEY = 1482847 limit 1");
            Console.Write(debugQuery);
            */
            if (args.Length < 1)
            {
                System.Console.WriteLine("Debe definir nombre de conexion y query");
            } else
            {
                try
                {
                    string result = ExecuteArgs(args);
                    Console.Write(result);
                } catch(Exception ex)
                {
                    System.Console.WriteLine(ex.Message);
                }

            }
        }

        static string ExecuteArgs(string[] args)
        {
            DB2.connectionString = ConfigurationSettings.AppSettings[args[0]];

            StringBuilder query = new StringBuilder();
            for (int i = 1; i < args.Length; i++)
            {
                query.Append(string.Format("{0} ", args[i]));
            }

            DB2 sql = new DB2();
            DataSet ds = sql.Query(query.ToString());
            string XmlFormatDataSet = ds.GetXml();

            return XmlFormatDataSet;
        }

        static string ExcecuteDebug(string query)
        {
            string XmlFormatDataSet = "";

            try
            {
                DB2 sql = new DB2();
                DataSet ds = sql.Query(query);
                XmlFormatDataSet = ds.GetXml();
            } catch(Exception ex)
            {
                XmlFormatDataSet = ex.Message;
            }
            

            return XmlFormatDataSet;
        }
    }
}
