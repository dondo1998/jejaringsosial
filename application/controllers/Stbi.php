<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

class Stbi extends CI_Controller {
    function index() {
        $data = array(
            'title' => 'Search File',
            'content' => 'stbi/search',
            'css' => 'stbi/search_css'
        );
        $this->load->view('template/index', $data);
    }

    public function searching()
    {
        $this->load->model('Stbi_Model');
        $keyword = $this->input->get('keyword');
        $files = $this->Stbi_Model->exec($keyword);

        $data = array(
            'title' => 'Searching for'.$keyword,
            'content' => 'stbi/searching',
            'files' => $files
        );
        $this->load->view('template/index', $data);
    }

    function search_rank() {
        $data = array(
            'title' => 'Page Rank Search',
            'content' => 'stbi/search_rank'
        );
        $this->load->view('template/index', $data);
    }

    public function searching_rank()
    {
        $this->load->model('Stbi_Model');
        $keyword = $this->input->get('keyword');
        $files = $this->Stbi_Model->page_rank($keyword);

        $data = array(
            'title' => 'Searching for'.$keyword,
            'content' => 'stbi/searching_rank',
            'files' => $files
        );
        $this->load->view('template/index', $data);
    }

    public function stem()
    {
        $data = array(
            'title' => 'Stem Kata',
            'content' => 'stbi/stem',
            'css' => 'stbi/stem_css',
            'js' => 'stbi/stem_js'
        );
        $this->load->view('template/index', $data);
    }

    public function stemming()
    {
        $this->load->model('CS_Model', 'CS');
        $word = $this->input->post('word');

        $stem = $this->CS->Enhanced_CS($word);

        echo json_encode(array('word' => $word, 'stem' => $stem));
    }

    public function tes()
    {
        $this->load->model('CS_Model', 'CS');
        $word = $this->uri->segment(3);

        $stem = $this->CS->Del_Derivation_Suffixes($word);

        echo json_encode(array('word' => $word, 'stem' => $stem));
    }

    public function upload_file()
    {
        $data = array(
            'title' => 'Upload File',
            'content' => 'stbi/file_upload',
        );
        $this->load->view('template/index', $data);
    }
    public function do_upload()
    {
        if (!isset($_FILES['myfile'])) {
            $this->session->set_flashdata('err', 'file error');
            redirect('index.php/stbi/upload_file');
            return false;
        }
        // ambil data file
        $namaFile = $_FILES['myfile']['name'];
        $namaSementara = $_FILES['myfile']['tmp_name'];

        // tentukan lokasi file akan dipindahkan
        $dirUpload = $_SERVER['DOCUMENT_ROOT']. '/stbi-tugas/assets/files/';

        // cek size
        if ($_FILES['myfile']['size'] > 4000000) {
            $this->session->set_flashdata('err', 'file terlalu besar');
            return redirect('index.php/stbi/upload_file');
        }

        // pindahkan file
        $terupload = move_uploaded_file($namaSementara, $dirUpload.$namaFile);

        if ($terupload) {
            $this->db->insert('upload', array('nama_file' => $namaFile, 'deskripsi' => $this->input->post('description'), 'tgl_upload' => date("Ymd")));

            // ambil teks
            $this->load->library('pdf');

            $this->pdf->setFilename($dirUpload.$namaFile);
            $this->pdf->decodePDF();
            
            $teks = $this->pdf->output();
            // hapus tanda baca
            $teks = str_replace("'", " ", $teks);
            $teks = str_replace("-", " ", $teks);
            $teks = str_replace(")", " ", $teks);
            $teks = str_replace("(", " ", $teks);
            $teks = str_replace("\"", " ", $teks);
            $teks = str_replace("/", " ", $teks);
            $teks = str_replace("=", " ", $teks);
            $teks = str_replace(".", " ", $teks);
            $teks = str_replace(",", " ", $teks);
            $teks = str_replace(":", " ", $teks);
            $teks = str_replace(";", " ", $teks);
            $teks = str_replace("!", " ", $teks);
            $teks = str_replace("?", " ", $teks); 
            $teks = str_replace(">", " ", $teks); 
            $teks = str_replace("<", " ", $teks); 

            //ubah ke huruf kecil 
            $teks = strtolower(trim($teks)); 

            $myArray = explode(" ", $teks); //proses tokenisasi

            //terapkan stop word removal
            $astoplist = array("a", "about", "above", "acara", "across", "ada", "adalah", "adanya", "after", "afterwards", "again", "against", "agar", "akan", "akhir", "akhirnya", "akibat", "aku", "all", "almost", "alone", "along", "already", "also", "although", "always", "am", "among", "amongst", "amoungst", "amount", "an", "and", "anda", "another", "antara", "any", "anyhow", "anyone", "anything", "anyway", "anywhere", "apa", "apakah", "apalagi", "are", "around", "as", "asal", "at", "atas", "atau", "awal", "b", "back", "badan", "bagaimana", "bagi", "bagian", "bahkan", "bahwa", "baik", "banyak", "barang", "barat", "baru", "bawah", "be", "beberapa", "became", "because", "become", "becomes", "becoming", "been", "before", "beforehand", "begitu", "behind", "being", "belakang", "below", "belum", "benar", "bentuk", "berada", "berarti", "berat", "berbagai", "berdasarkan", "berjalan", "berlangsung", "bersama", "bertemu", "besar", "beside", "besides", "between", "beyond", "biasa", "biasanya", "bila", "bill", "bisa", "both", "bottom", "bukan", "bulan", "but", "by", "call", "can", "cannot", "cant", "cara", "co", "con", "could", "couldnt", "cry", "cukup", "dalam", "dan", "dapat", "dari", "datang", "de", "dekat", "demikian", "dengan", "depan", "describe", "detail", "di", "dia", "diduga", "digunakan", "dilakukan", "diri", "dirinya", "ditemukan", "do", "done", "down", "dua", "due", "dulu", "during", "each", "eg", "eight", "either", "eleven", "else", "elsewhere", "empat", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "gedung", "get", "give", "go", "had", "hal", "hampir", "hanya", "hari", "harus", "has", "hasil", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "hidup", "him", "himself", "hingga", "his", "how", "however", "hubungan", "hundred", "ia", "ie", "if", "ikut", "in", "inc", "indeed", "ingin", "ini", "interest", "into", "is", "it", "its", "itself", "itu", "jadi", "jalan", "jangan", "jauh", "jelas", "jenis", "jika", "juga", "jumat", "jumlah", "juni", "justru", "juta", "kalau", "kali", "kami", "kamis", "karena", "kata", "katanya", "ke", "kebutuhan", "kecil", "kedua", "keep", "kegiatan", "kehidupan", "kejadian", "keluar", "kembali", "kemudian", "kemungkinan", "kepada", "keputusan", "kerja", "kesempatan", "keterangan", "ketiga", "ketika", "khusus", "kini", "kita", "kondisi", "kurang", "lagi", "lain", "lainnya", "lalu", "lama", "langsung", "lanjut", "last", "latter", "latterly", "least", "lebih", "less", "lewat", "lima", "ltd", "luar", "made", "maka", "mampu", "mana", "mantan", "many", "masa", "masalah", "masih", "masing-masing", "masuk", "mau", "maupun", "may", "me", "meanwhile", "melakukan", "melalui", "melihat", "memang", "membantu", "membawa", "memberi", "memberikan", "membuat", "memiliki", "meminta", "mempunyai", "mencapai", "mencari", "mendapat", "mendapatkan", "menerima", "mengaku", "mengalami", "mengambil", "mengatakan", "mengenai", "mengetahui", "menggunakan", "menghadapi", "meningkatkan", "menjadi", "menjalani", "menjelaskan", "menunjukkan", "menurut", "menyatakan", "menyebabkan", "menyebutkan", "merasa", "mereka", "merupakan", "meski", "might", "milik", "mill", "mine", "minggu", "misalnya", "more", "moreover", "most", "mostly", "move", "much", "mulai", "muncul", "mungkin", "must", "my", "myself", "nama", "name", "namely", "namun", "nanti", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "oleh", "on", "once", "one", "only", "onto", "or", "orang", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own", "pada", "padahal", "pagi", "paling", "panjang", "para", "part", "pasti", "pekan", "penggunaan", "penting", "per", "perhaps", "perlu", "pernah", "persen", "pertama", "pihak", "please", "posisi", "program", "proses", "pula", "pun", "punya", "put", "rabu", "rasa", "rather", "re", "ribu", "ruang", "saat", "sabtu", "saja", "salah", "sama", "same", "sampai", "sangat", "satu", "saya", "sebab", "sebagai", "sebagian", "sebanyak", "sebelum", "sebelumnya", "sebenarnya", "sebesar", "sebuah", "secara", "sedang", "sedangkan", "sedikit", "see", "seem", "seemed", "seeming", "seems", "segera", "sehingga", "sejak", "sejumlah", "sekali", "sekarang", "sekitar", "selain", "selalu", "selama", "selasa", "selatan", "seluruh", "semakin", "sementara", "sempat", "semua", "sendiri", "senin", "seorang", "seperti", "sering", "serious", "serta", "sesuai", "setelah", "setiap", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "suatu", "such", "sudah", "sumber", "system", "tahu", "tahun", "tak", "take", "tampil", "tanggal", "tanpa", "tapi", "telah", "teman", "tempat", "ten", "tengah", "tentang", "tentu", "terakhir", "terhadap", "terjadi", "terkait", "terlalu", "terlihat", "termasuk", "ternyata", "tersebut", "terus", "terutama", "tetapi", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "tidak", "tiga", "tinggal", "tinggi", "tingkat", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "ujar", "umum", "un", "under", "until", "untuk", "up", "upaya", "upon", "us", "usai", "utama", "utara", "very", "via", "waktu", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "wib", "will", "with", "within", "without", "would", "ya", "yaitu", "yakni", "yang", "yet", "you", "your", "yours", "yourself", "yourselves");

            $filteredarray = array_diff($myArray, $astoplist); //remove stopword
            
            // Stemming
            $this->load->model('CS_Model', 'CS');

            foreach($filteredarray as $filteredarray){
                if (strlen($filteredarray) >=4) {
                        $hasil=$this->CS->Enhanced_CS(trim($filteredarray));

                        $this->db->insert('dokumen', array('nama_file' => $namaFile, 'token' => trim($filteredarray), 'tokenstem' => $hasil));
                }
            }

            $this->load->model('Stbi_Model');
            $this->Stbi_Model->hitungBobot($namaFile);
            $this->Stbi_Model->hitungVektor($namaFile);

            return redirect('index.php/stbi/list_file');
        }
    }

    public function list_file()
    {
        $files = $this->db->get('upload')->result();
        $data = array(
            'title' => 'List File',
            'content' => 'stbi/list_file',
            'files' => $files
        );
        $this->load->view('template/index', $data);
    }

    public function truncate()
    {
        if ($this->input->post('password') == 123456) {
            $this->db->truncate('dokumen');
            $this->db->truncate('tbcache');
            $this->db->truncate('tbvektor');
            $this->db->truncate('upload');
            $this->db->truncate('tbindex');
        }

        echo "<form method='POST'>
            <input type='password' name='password'/>
            <input type='submit' value='hapus'/>
        </form>";
    }
}
?>