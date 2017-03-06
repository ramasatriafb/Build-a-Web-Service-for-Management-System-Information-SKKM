<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyModel extends CI_Model {

    var $client_service = "frontend-client";
    var $auth_key       = "service_skkm";

    public function check_auth_client(){
        $client_service = $this->input->get_request_header('Client-Service', TRUE);
        $auth_key  = $this->input->get_request_header('Auth-Key', TRUE);
        if($client_service == $this->client_service && $auth_key == $this->auth_key){
            return true;
        } else {
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        }
    }

    public function login($username,$password)
    {
        $q  = $this->db->select('PASSWORD,ID')->from('USERS')->where('USERNAME',$username)->get()->row();
        if($q == ""){
            return array('status' => 204,'message' => 'Username not found.');
        } else {
            $hashed_password = $q->PASSWORD;
            $id              = $q->ID;
            if (md5($password) == $hashed_password) {
               $last_login = date('d-m-y : H-i-s');
               $token = crypt(substr( md5(rand()), 0, 7));
               $expired_at = date("d-m-y : H-i-s", strtotime('+12 hours'));
               $this->db->trans_start();
               $sql = "UPDATE USERS SET LAST_LOGIN = TO_DATE('".$last_login."','DD-MM-YYYY HH24:MI:SS') WHERE id = (SELECT ID FROM USERS WHERE ID = $id)";
               $this->db->query($sql);
               //where('ID',$id)->update('USERS',array('LAST_LOGIN' => $last_login));
               $this->db->insert('USERS_AUTHENTICATION',array('USERS_ID' => $id,'TOKEN' => $token,'EXPIRED_AT' => $expired_at));
               if ($this->db->trans_status() === FALSE){
                  $this->db->trans_rollback();
                  return array('status' => 500,'message' => 'Internal server error.');
               } else {
                  $this->db->trans_commit();
                  return array('status' => 200,'message' => 'Successfully login.','ID' => $id, 'TOKEN' => $token);
               }
            } else {
               return array('status' => 204,'message' => 'Wrong password.');
            }
        }
    }

    public function logout()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $sql = "DELETE FROM USERS_AUTHENTICATION WHERE users_id = '".$users_id."' AND token = '".$token."'";
        $this->db->query($sql);
        //where('USERS_ID',$users_id)->where('TOKEN',$token)->delete('USERS_AUTHENTICATION');
        return array('status' => 200,'message' => 'Successfully logout.');
    }

    public function auth()
    {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $sql = "SELECT expired_at FROM users_authentication WHERE users_id = '".$users_id."' AND token = '".$token."'";
        $q  = $this->db->query($sql)->result_array();
        foreach ($q as $q1) {
            
        
        //select('expired_at')->from('users_authentication')->where('users_id',$users_id)->where('token',$token)->get()->row();
        if($q1 == ""){
            return json_output(401,array('status' => 401,'message' => 'Unauthorized.'));
        } else {
            if($q1['EXPIRED_AT'] < date('d-m-y : H-i-s')){
                return json_output(401,array('status' => 401,'message' => 'Your session has been expired.'));
            } else {
                $updated_at = date('d-m-y : H-i-s');
                $expired_at = date("d-m-y : H-i-s", strtotime('+12 hours'));
                $sql = "UPDATE users_authentication SET expired_at = '".$expired_at."',updated_at = TO_DATE('".$updated_at."','DD-MM-YYYY HH24:MI:SS')
                 WHERE users_id = '".$users_id."' AND token = '".$token."' ";
                $this->db->query($sql);
                //where('users_id',$users_id)->where('token',$token)->update('users_authentication',array('expired_at' => $expired_at,'updated_at' => $updated_at));
                return array('status' => 200,'message' => 'Authorized.');
            }
        }
        }
    }


    //Pegawai
    public function pegawai_all_data()
    {
        return $this->db->select('NIP,NAMA,EMAIL')->from('PEGAWAI')->order_by('NIP','asc')->get()->result();
    }

    public function pegawai_detail_data($id)
    {
        return $this->db->select('NIP,NAMA,EMAIL')->from('PEGAWAI')->where('NIP',$id)->order_by('NIP','asc')->get()->row();
    }

    public function pegawai_create_data($data)
    {
        $this->db->insert('PEGAWAI',$data);
        return array('status' => 201,'message' => 'Data Pegawai telah ditambahkan.');
    }

    public function pegawai_update_data($id,$data)
    {
        
        $this->db->where('NIP',$id)->update('PEGAWAI',$data);
        return array('status' => 200,'message' => 'Data Pegawai telah diupdate.');
    }

    public function pegawai_delete_data($id)
    {
        $sql ="DELETE FROM pegawai WHERE nip = '".$id."' ";
        $this->db->query($sql);
        //where('nip',$id)->delete('pegawai');
        return array('status' => 200,'message' => 'Data Pegawai telah dihapus.');
    }

    // User
    public function user_all_data()
    {
        return $this->db->select('ID,EMAIL')->from('USER_')->order_by('ID','asc')->get()->result();
    }

    public function user_detail_data($id)
    {
        return $this->db->select('ID,EMAIL')->from('USER_')->where('ID',$id)->order_by('ID','asc')->get()->row();
    }

    public function user_create_data($params,$pass)
    {
        $sql ="INSERT INTO USER_ (id,email,password)"."VALUES('".$params['ID']."','".$params['EMAIL']."','".SHA1($pass['PASSWORD'])."')";
        $this->db->query($sql);
        return array('status' => 201,'message' => 'Data User telah ditambahkan.');
    }

    public function user_update_data($id,$params)
    {
        $sql = "UPDATE USER_ SET EMAIL = '".$params['EMAIL']."',PASSWORD = '".SHA1($params['PASSWORD'])."'
     WHERE id = (SELECT id FROM USER_ WHERE id = $id)";
        $query = $this->db->query($sql);
        return array('status' => 200,'message' => 'Data User telah diupdate.');
    }

    public function user_delete_data($id)
    {
        $sql ="DELETE FROM user_ WHERE id = '".$id."' ";
        $this->db->query($sql);
        //$this->db->where('id',$id)->delete('user');
        return array('status' => 200,'message' => 'Data User telah dihapus.');
    }

    // Mahasiswa
    public function mahasiswa_all_data()
    {
        return $this->db->select('NRP,NAMA,KELAS_ID,JENIS_KELAMIN_ID,EMAIL,STATUS')->from('MAHASISWA')->order_by('NRP','asc')->get()->result();
    }

    public function mahasiswa_detail_data($id)
    {
        return $this->db->select('NRP,NAMA,KELAS_ID,JENIS_KELAMIN_ID,EMAIL,STATUS')->from('MAHASISWA')->where('NRP',$id)->order_by('NRP','asc')->get()->row();
    }

    public function mahasiswa_create_data($data)
    {
        $this->db->insert('MAHASISWA',$data);
        return array('status' => 201,'message' => 'Data Mahasiswa telah ditambahkan.');
    }

    public function mahasiswa_update_data($id,$data)
    {
        $this->db->where('NRP',$id)->update('MAHASISWA',$data);
        return array('status' => 200,'message' => 'Data Mahasiswa telah diupdate.');
    }

    public function mahasiswa_delete_data($id)
    {
        $sql ="DELETE FROM mahasiswa WHERE nrp = '".$id."' ";
        $this->db->query($sql);
        //$this->db->where('nrp',$id)->delete('mahasiswa');
        return array('status' => 200,'message' => 'Data Mahasiswa telah dihapus.');
    }

    //Kelas
    public function kelas_all_data()
    {
        return $this->db->select('ID,NAMA,PROGRAM_STUDI_ID,JURUSAN_ID')->from('KELAS')->order_by('ID','asc')->get()->result();
    }

    public function kelas_detail_data($id)
    {
        return $this->db->select('ID,NAMA,PROGRAM_STUDI_ID,JURUSAN_ID')->from('KELAS')->where('ID',$id)->order_by('ID','asc')->get()->row();
    }

    public function kelas_create_data($data)
    {
        $this->db->insert('KELAS',$data);
        return array('status' => 201,'message' => 'Data Kelas telah ditambahkan.');
    }

    public function kelas_update_data($id,$data)
    {
        $this->db->where('ID',$id)->update('KELAS',$data);
        return array('status' => 200,'message' => 'Data Kelas telah diupdate.');
    }

    public function kelas_delete_data($id)
    {
        $sql ="DELETE FROM kelas WHERE id = '".$id."' ";
        $this->db->query($sql);
        //$this->db->where('id',$id)->delete('kelas');
        return array('status' => 200,'message' => 'Data Kelas telah dihapus.');
    }

    public function poin_mahasiswa($nrp)
    {
        return $this->db->select('NRP,NAMA,POIN')->from('V_LAPORAN_REKAP_KEGIATAN')->where('NRP',$nrp)->order_by('NRP','asc')->get()->result();
    }


}
