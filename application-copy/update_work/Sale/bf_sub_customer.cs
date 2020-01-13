using System;
using System.Collections;
using System.Collections.Generic;
using System.Text;
namespace Pharmacy
{
    #region Bf_sub_customer
    public class Bf_sub_customer
    {
        #region Member Variables
        protected int _id;
        protected string _sub_customer_name;
        protected string _sub_customer_phone_number;
        protected int _customer_id;
        protected int _status;
        protected unknown _created_date;
        protected unknown _updated_date;
        #endregion
        #region Constructors
        public Bf_sub_customer() { }
        public Bf_sub_customer(string sub_customer_name, string sub_customer_phone_number, int customer_id, int status, unknown created_date, unknown updated_date)
        {
            this._sub_customer_name=sub_customer_name;
            this._sub_customer_phone_number=sub_customer_phone_number;
            this._customer_id=customer_id;
            this._status=status;
            this._created_date=created_date;
            this._updated_date=updated_date;
        }
        #endregion
        #region Public Properties
        public virtual int Id
        {
            get {return _id;}
            set {_id=value;}
        }
        public virtual string Sub_customer_name
        {
            get {return _sub_customer_name;}
            set {_sub_customer_name=value;}
        }
        public virtual string Sub_customer_phone_number
        {
            get {return _sub_customer_phone_number;}
            set {_sub_customer_phone_number=value;}
        }
        public virtual int Customer_id
        {
            get {return _customer_id;}
            set {_customer_id=value;}
        }
        public virtual int Status
        {
            get {return _status;}
            set {_status=value;}
        }
        public virtual unknown Created_date
        {
            get {return _created_date;}
            set {_created_date=value;}
        }
        public virtual unknown Updated_date
        {
            get {return _updated_date;}
            set {_updated_date=value;}
        }
        #endregion
    }
    #endregion
}