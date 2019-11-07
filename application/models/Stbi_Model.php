<?php
class Stbi_Model extends CI_Model {
    public function exec($keyword)
    {
        $data = array();
        $resCache = $this->db->from('tbcache')->where(array('Query' => $keyword))->order_by('Value', 'desc')->get();
        // $resCache = mysql_query("SELECT *  FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC");
        // $num_rows = mysql_num_rows($resCache);
        if ($resCache->num_rows() > 0) {
    
            //tampilkan semua berita yang telah terurut
            foreach ($resCache->result_array() as $key => $value) {
                $docId = $value['DocId'];
                $sim = $value['Value'];
                        
                $resBerita = $this->db->get_where('upload', array('nama_file' => $docId))->row_array();
                // $resBerita = mysql_query("SELECT nama_file,deskripsi FROM upload WHERE nama_file = '$docId'");
                // $rowBerita = mysql_fetch_array($resBerita);
                    
                $data[] = array(
                    'docId' => $docId,
                    'sim' => $sim,
                    'nama_file' => $resBerita['nama_file'],
                    'deskripsi' => $resBerita['deskripsi']
                );
                
            }//end while (rowCache = mysql_fetch_array($resCache))
        } else {
            $this->hitungsim($keyword);

            //pasti telah ada dalam tbcache		
            $resCache = $this->db->from('tbcache')->where(array('Query' => $keyword))->order_by('Value', 'desc')->get()->result_array();
            // $resCache = mysql_query("SELECT *  FROM tbcache WHERE Query = '$keyword' ORDER BY Value DESC");
            // $num_rows = mysql_num_rows($resCache);
            
            foreach ($resCache as $key => $value) {
                $docId = $value['DocId'];
                $sim = $value['Value'];
                        
                    //ambil berita dari tabel tbberita, tampilkan
                    $resBerita = $this->db->get_Where('upload', array('nama_file' => $docId))->row_array();
                    // $resBerita = mysql_query("SELECT nama_file,deskripsi FROM upload WHERE nama_file = '$docId'");
                    // $rowBerita = mysql_fetch_array($resBerita);
                        
                    $data[] = array(
                        'docId' => $docId,
                        'sim' => $sim,
                        'nama_file' => $resBerita['nama_file'],
                        'deskripsi' => $resBerita['deskripsi']
                    );

                    // $judul = $rowBerita['nama_file'];
                    // $berita = $rowBerita['deskripsi'];
                        
                    // print($docId . ". (" . $sim . ") <font color=blue><b><a href=" . $judul . "> </b></font><br />");
                    // print($berita . "<hr /></a>");
            
            } //end while
        }

        return $data;
    }

    function hitungsim($query) {
        //ambil jumlah total dokumen yang telah diindex (tbindex atau tbvektor), n

        $n = $this->db->get('tbvektor')->num_rows();
        // $resn = mysql_query("SELECT Count(*) as n FROM tbvektor");
        // $rown = mysql_fetch_array($resn);	
        // $n = $rown['n'];

        // print_r($resn);

        //terapkan preprocessing terhadap $query
        $aquery = explode(" ", $query);
        
        //hitung panjang vektor query
        $panjangQuery = 0;
        $aBobotQuery = array();
        
        for ($i=0; $i < count($aquery); $i++) {
            //hitung bobot untuk term ke-i pada query, log(n/N);
            //hitung jumlah dokumen yang mengandung term tersebut
            $NTerm = $this->db->from('tbindex')->like('Term', "$aquery[$i]")->get()->num_rows();
    //         $resNTerm = mysql_query("SELECT Count(*) as N from tbindex WHERE Term like '%$aquery[$i]%'");
    // //		echo "query >SELECT Count(*) as N from tbindex WHERE Term like '%$aquery[$i]%'";
    //         $rowNTerm = mysql_fetch_array($resNTerm);	
    //         $NTerm = $rowNTerm['N'] ;
            $idf = $NTerm == 0 ? 0 : log($n/$NTerm);
            
            //simpan di array		
            $aBobotQuery[] = $idf;
            
            $panjangQuery = $panjangQuery + $idf * $idf;		
        }
        
        $panjangQuery = sqrt($panjangQuery);
        
        $jumlahmirip = 0;
        
        //ambil setiap term dari DocId, bandingkan dengan Query
        $resDocId = $this->db->from('tbvektor')->order_by('DocId', 'asc')->get()->result_array();
        // $resDocId = mysql_query("SELECT * FROM tbvektor ORDER BY DocId");
        foreach ($resDocId as $key => $rowDocId) {
            $dotproduct = 0;
                
            $docId = $rowDocId['DocId'];
            $panjangDocId = $rowDocId['Panjang'];
            
            $resTerm = $this->db->get_where('tbindex', array('DocId' => $docId))->result_array();
            // $resTerm = mysql_query("SELECT * FROM tbindex WHERE DocId = '$docId'");
            //	echo "query ->SELECT * FROM tbindex WHERE DocId = '$docId'".'<br>';
            
            foreach ($resTerm as $rowTerm) {
                for ($i=0; $i<count($aquery); $i++) {
                //jika term sama
                if ($rowTerm['Term'] == $aquery[$i]) {
                    $dotproduct = $dotproduct + $rowTerm['Bobot'] * $aBobotQuery[$i];		
                    // echo "hasil =".$dotproduct.'<br>';
                    //		echo "1-->".$rowTerm['Term'];
                    //	echo "2-->".	$aquery[$i].'<br>';
                    }
                } //end for $i		
            } //end while ($rowTerm)
            
            if ($dotproduct != 0) {
                $sim = $dotproduct / ($panjangQuery * $panjangDocId);	
                //echo "insert >>INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', '$docId', $sim)";
                //simpan kemiripan > 0  ke dalam tbcache
                $this->db->insert('tbcache', array('Query' => $query, 'DocId' => $docId, 'Value' => $sim));
                // $resInsertCache = mysql_query("INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', '$docId', $sim)");
                $jumlahmirip++;
            } 
                
            if ($jumlahmirip == 0) {
                $this->db->insert('tbcache', array('Query' => $query, 'DocId' => 0, 'Value' => 0));
                // $resInsertCache = mysql_query("INSERT INTO tbcache (Query, DocId, Value) VALUES ('$query', 0, 0)");
            }	
        } //end while $rowDocId
    } //end hitungSim()

    public function hitungBobot($nama_file)
    {
        $this->db->query("INSERT INTO `tbindex`(`Term`, `DocId`, `Count`) SELECT `token`,`nama_file`,count(*) FROM `dokumen` where nama_file like '$nama_file' group by `nama_file`,token");
	
        //berapa jumlah DocId total?, n
        // $n = $this->db->select("DISTINCT(DocId)")->from('tbindex')->get()->num_rows();
        $n = $this->db->get_where('tbindex', array('DocId' => $nama_file))->num_rows();
        
        //ambil setiap record dalam tabel tbindex
        //hitung bobot untuk setiap Term dalam setiap DocId
        // $resBobot = mysql_query("SELECT * FROM tbindex ORDER BY Id");
        $resBobot = $this->db->from('tbindex')->where('DocId', $nama_file)->order_by('id', 'asc')->get()->result_array();
        // $num_rows = mysql_num_rows($resBobot);
        // print("Terdapat " . $num_rows . " Term yang diberikan bobot. <br />");

        foreach ($resBobot as $rowbobot) {
            //$w = tf * log (n/N)
            $term = $rowbobot['Term'];		
            $tf = $rowbobot['Count'];
            $id = $rowbobot['Id'];
            
            //berapa jumlah dokumen yang mengandung term tersebut?, N
            // $resNTerm = mysql_query("SELECT Count(*) as N FROM tbindex WHERE Term = '$term'");
            // $rowNTerm = mysql_fetch_array($resNTerm);
            // $NTerm = $rowNTerm['N'];
            $NTerm = $this->db->get_where('tbindex', array('Term' => $term))->num_rows();
            
            $w = $tf * log($n/$NTerm);
            
            //update bobot dari term tersebut
            // $resUpdateBobot = mysql_query("UPDATE tbindex SET Bobot = $w WHERE Id = $id");	
            $this->db->set(array('Bobot' => $w))->where('Id', $id)->update('tbindex');
        } //end while $rowbobot
    }

    public function hitungVektor($nama_file)
    {
        // mysql_query("TRUNCATE TABLE tbvektor");
        // $this->db->truncate('tbvektor');

        //ambil setiap DocId dalam tbindex
        //hitung panjang vektor untuk setiap DocId tersebut
        //simpan ke dalam tabel tbvektor
        // $resDocId = mysql_query("SELECT DISTINCT DocId FROM tbindex");
        // $resDocId = $this->db->select('DISTINCT DocId', false)->from('tbindex')->get();
        
        // foreach ($resDoccId as $rowDocId) {
        // // while($rowDocId = mysql_fetch_array($resDocId)) {
        //     $docId = $rowDocId['DocId'];
        
        //     // $resVektor = mysql_query("SELECT Bobot FROM tbindex WHERE DocId = '$docId'");
        //     $resVektor = $this->db->get_where('tbindex', array('DocId' => $docId))->result_array();
            
        //     //jumlahkan semua bobot kuadrat 
        //     $panjangVektor = 0;
        //     foreach ($resVektor as $rowVektor) {
        //         $panjangVektor = $panjangVektor + $rowVektor['Bobot']  *  $rowVektor['Bobot'];	
        //     }
            
        //     //hitung akarnya		
        //     $panjangVektor = sqrt($panjangVektor);
            
        //     //masukkan ke dalam tbvektor		
        //     // $resInsertVektor = $this->db->query("INSERT INTO tbvektor (DocId, Panjang) VALUES ('$docId', $panjangVektor)");	
        //     $this->db->insert('tbvektor', array('DocId' => $docId, 'Panjang' => $panjangVektor));
        // } //end while $rowDocId

        $resVektor = $this->db->get_where('tbindex', array('DocId' => $nama_file))->result_array();
        $panjangVektor = 0;
        foreach ($resVektor as $rowVektor) {
            $panjangVektor = $panjangVektor + $rowVektor['Bobot']  *  $rowVektor['Bobot'];	
        }

        $panjangVektor = sqrt($panjangVektor);
        $this->db->insert('tbvektor', array('DocId' => $nama_file, 'Panjang' => $panjangVektor));
    }

    public function page_rank($keyword)
    {
        $data = $this->db->query("SELECT distinct nama_file,token,tokenstem FROM `dokumen` WHERE MATCH (token,tokenstem) AGAINST ('$keyword' IN BOOLEAN MODE)");
        return $data;
        // echo $sql;
        // $result = $conn->query($sql);
        // if ($data->num_rows > 0) {
        //     while($row = $result->fetch_assoc()) {
        //         echo "Nama file: " . $row["nama_file"]. " - Token: " . $row["token"]. " " . $row["tokenstem"]. "<br>";
        //     }
        // } else {
        //     echo "0 results";
        // }
    }
}
?>