<?php
namespace core\lib;
class upload { 

    private $conf = array(); // 配置项
    private $originFile; // 源文件信息
    private $files = array(); // 上传后的文件信息


    public function __construct( $conf = array() ) {
        
        if( !empty($conf) ) {
            $this->conf = $conf;
        } else {
            $this->conf = array(
                'path'  => "/Uploads/", // 上传路径
                'israndName' => true
                );
        }

        if( $_FILES ) {

            $this->originFile = $_FILES;

        }

    }
    /**
     *  上传操作
     * 
     */
    public function doUpload() {

        $this->save();

        return $this->files;
    }
    /**
     * 保存上传文件到上传路径
     */
    public function save() {

        foreach($this->originFile as $key => $val) {
            
            if( is_array($val['name']) ) {

                for ($i=0; $i < count($val['name']); $i++) {

                    $this->files[$key][$i]= array(
                        'name' => $val['name'][$i],
                        'saveName' => $val['name'][$i],
                        'savePath' => $this->conf['path'],
                        'type' => $val['type'][$i],
                        'error' => $val['error'][$i],
                        'size' => $val['size'][$i]
                        );

                    if( $this->conf['israndName'] ) {

                        $this->files[$key][$i]['saveName'] = $this->randName().'.'.$this->getExt($this->files[$key][$i]['name']);
                        move_uploaded_file(
                            $val['tmp_name'][$i],
                            BASE_PATH.$this->conf['path'].$this->files[$key][$i]['saveName']
                            );
                    } else {
                        move_uploaded_file(
                            $val['tmp_name'][$i],
                            BASE_PATH.$this->conf['path'].$this->files[$key][$i]['saveName']
                            );
                    } 
                        
                }
            } else {

               $this->files[$key]= array(
                    'name' => $val['name'],
                    'saveName' => $val['name'],
                    'savePath' => $this->conf['path'],
                    'type' => $val['type'],
                    'error' => $val['error'],
                    'size' => $val['size']
                    );

                if( $this->conf['israndName'] ) {

                    $this->files[$key]['saveName'] = $this->randName().'.'.$this->getExt($this->files[$key]['name']);
                    move_uploaded_file(
                        $val['tmp_name'],
                        BASE_PATH.$this->conf['path'].$this->files[$key]['saveName']
                        );
                } else {

                    move_uploaded_file(
                        $val['tmp_name'],
                        BASE_PATH.$this->conf['path'].$this->files[$key]['saveName']
                        );
                }
            }
        }

    }
    /**
     *  获取随机的字符串
     */
    public function randName() {

        $str = "0123456789zxcvbnmasdfghjklqwertyuiop";
        $rand = '';
        for ($i=0; $i < 4; $i++) { 
            $rand .= $str[mt_rand(0 ,strlen($str)-1)];
        }
        return md5($rand);
    }
    /**
     *  获取文件扩展名
     * @param $filename [string] 完整文件名
     * @return string
     */
    public function getExt( $filename ) {
        $str = explode('.', $filename);
        return $str[1];
    }
}