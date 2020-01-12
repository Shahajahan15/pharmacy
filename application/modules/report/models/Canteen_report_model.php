<?php 
class Canteen_report_model extends CI_model{

	  public function getPurchaseList($condition,$first_date,$second_date,$limit,$offset){
       return $this->db
       ->select('bcspm.purchase_no,
        bcspm.purchase_date,
        sum(bcspd.total_price) as total_price')
       ->from('bf_canteen_stock_purchase_mst as bcspm')
       ->join('bf_canteen_stock_purchase_dtls as bcspd','bcspm.id=bcspd.master_id')
       ->group_by('bcspm.purchase_no')
       ->where($condition)
       ->where('bcspm.purchase_date >=',$first_date)
       ->where('bcspm.purchase_date <=',$second_date)
       ->limit($limit,$offset)
       ->get()
       ->result();
  }

  //Canteen Cash Out

  public function canteenCashOut(/*$condition,*/$first_date,$second_date,$limit,$offset){
       $record1 = $this->db
       ->select('bcspm.purchase_no as pid,
        bcspm.purchase_date as mydate,
        0 as type,
        sum(bcspd.total_price) as total_price')
       ->from('bf_canteen_stock_purchase_mst as bcspm')
       ->join('bf_canteen_stock_purchase_dtls as bcspd','bcspm.id=bcspd.master_id')
       ->group_by('bcspm.purchase_no')
       //->where($condition)
       ->where('bcspm.is_return',0)
       ->where('bcspm.created_time >=',$first_date)
       ->where('bcspm.created_time <=',$second_date)
       ->limit($limit,$offset)
       ->get()
       ->result();


       $record2 = $this->db
       ->select('csrmst.id as pid,
        csrmst.return_date as mydate,
        1 as type,
        csrmst.return_tot_amunt as total_price')
       ->from('bf_canteen_sales_return_mst as csrmst')
       //->where($condition)
       ->where('csrmst.created_at >=',$first_date)
       ->where('csrmst.created_at <=',$second_date)
       ->limit($limit,$offset)
       ->get()
       ->result();

     return  $query = array_merge($record1,$record2);
  }

     public function canteenCashIn(/*$conditions,*/$first_date,$second_date,$limit,$offset){
      
       $record1 = $this->db
       ->select('spay.paid_amount as amount,
        spay.created_at as mydate,
        spay.master_id as master_id')
       ->from('bf_canteen_sales_payments as spay')
       ->where('spay.created_at >=',$first_date)
       ->where('spay.created_at <=',$second_date)
       ->limit($limit,$offset)
       ->get()
       ->result();

       $record2 = $this->db
       ->select('
        bcspd.total_price as amount,
        bcspm.created_time as mydate,
        999999999999 as master_id
        ')
       ->from('bf_canteen_stock_purchase_mst as bcspm')
       ->join('bf_canteen_stock_purchase_dtls as bcspd','bcspm.id=bcspd.master_id')
       ->group_by('bcspm.purchase_no')
       ->where('bcspm.is_return',1)
       ->where('bcspm.created_time >=',$first_date)
       ->where('bcspm.created_time <=',$second_date)
       ->limit($limit,$offset)
       ->get()
       ->result();


       return  $query = array_merge($record1,$record2);


  }
}