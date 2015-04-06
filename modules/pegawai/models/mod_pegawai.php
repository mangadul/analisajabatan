<?php

class mod_pegawai extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }
    
    function MasterPegawai($q, $start, $limit, $sort, $dir){
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
					A.*, B.nama AS nama_jabatan, C.golongan AS nama_golongan, D.nama as nama_kelurahan, 
					E.status_pns, F.agama, G.nama as pendidikan,
					CONCAT(A.tgl_lahir, '-', A.bulan_lahir, '-', A.tahun_lahir) AS tgl_lahir_gab
                FROM m_pegawai A 
				LEFT JOIN m_jabatan B ON A.kode_jabatan = B.kode
				LEFT JOIN m_golongan C ON A.id_golongan = C.id
				LEFT JOIN m_kelurahan D ON A.kelurahan = D.id
				LEFT JOIN m_sts_pns E ON A.id_status_pns = E.id
				LEFT JOIN m_agama F ON A.agama = F.id
				LEFT JOIN m_pendidikan G ON A.pendidikan_terakhir = G.id
				WHERE (A.nip LIKE '%$q%' OR A.nama LIKE '%$q%' OR A.alamat_asal LIKE '%q%' OR A.alamat_sekarang LIKE '%$q%')
				ORDER by $sort $dir
                LIMIT $start,$limit";    
        
        $query[0] = $this->db->query($sql); 
        $query[0] = $query[0]->result();
        
        $query[1] = $this->db->query("SELECT FOUND_ROWS() AS recordcount"); 
        $query[1] = $query[1]->result();
         
        return $query;        
    }
    
    function HapusPegawai($id) {
        $this->db->delete('m_pegawai',array('id'=>$id));
    }
        
    
    function LoadJabatan($q) {
        $length = strlen($q);
        $sql = "SELECT kode, SUBSTRING(nama, 1,55) AS nama FROM m_jabatan
                WHERE nama LIKE '%$q%'
                ORDER BY nama
                LIMIT 20";
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
	
	
	function LoadGolongan($q) {
        $length = strlen($q);
        $sql = "SELECT id, golongan FROM m_golongan
                WHERE golongan LIKE '%$q%'
                ORDER BY id
                LIMIT 20";
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
	
	function LoadPendidikan($q) {
        $length = strlen($q);
        $sql = "SELECT id, nama FROM m_pendidikan
                WHERE nama LIKE '%$q%'
                ORDER BY id
                LIMIT 20";
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
	
	function LoadStatusPNS($q) {
        $length = strlen($q);
        $sql = "SELECT id, status_pns FROM m_sts_pns
                WHERE status_pns LIKE '%$q%'
                ORDER BY id
                LIMIT 10";
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
	
    function InsertPegawai($id_pegawai, $nip, $kode_jabatan, $id_golongan, $nama, $alamat_asal, $alamat_sekarang, $kecamatan, $kelurahan, $rw, $rt, $no_rmh, $no_telp, $kode_pos, $email, $agama, $jenis_kelamin, $tempat_lahir, $tgl_lahir, $bulan_lahir, $tahun_lahir, $pendidikan_terakhir, $status_marital, $id_status_pns
) {        
        $data = array(
		
		'id_pegawai' => $id_pegawai,
		'nip' => $nip,
		'kode_jabatan' => $kode_jabatan,
		'id_golongan' => $id_golongan,
		'nama' => $nama,
		'alamat_asal' => $alamat_asal,
		'alamat_sekarang' => $alamat_sekarang,
		'kecamatan' => $kecamatan,
		'kelurahan' => $kelurahan,
		'rw' => $rw,
		'rt' => $rt,
		'no_rmh' => $no_rmh,
		'no_telp' => $no_telp,
		'kode_pos' => $kode_pos,
		'email' => $emial,
		'agama' => $agama,
		'jenis_kelamin' => $jenis_kelamin,
		'tempat_lahir' => $tempat_lahir,
		'tgl_lahir' => $tgl_lahir,
		'bulan_lahir' => $bulan_lahir,
		'tahun_lahir' => $tahun_lahir,
		'pendidikan_terakhir' => $pendidikan_terakhir,
		'status_marital' => $status_marital,
		
		'id_status_pns' => $id_status_pns
            
			
        );
		
        $this->db->insert('m_kelurahan', $data);
    }
	
	function UpdatePegawai($id_pegawai, $nip, $kode_jabatan, $id_golongan, $nama, $alamat_asal, $alamat_sekarang, $kecamatan, $kelurahan, $rw, $rt, $no_rmh, $no_telp, $kode_pos, $email, $agama, $jenis_kelamin, $tempat_lahir, $tgl_lahir, $bulan_lahir, $tahun_lahir, $pendidikan_terakhir, $status_marital, $id_status_pns) {
		$sql = "UPDATE m_pegawai SET id_kecamatan = '$id_kecamatan', nama = '$nama' WHERE id='$id'"; 
		$this->db->query($sql);
	}
}

?>