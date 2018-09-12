<?php
namespace app\admin\controller;
use app\admin\controller\Base;
use app\admin\model\Exam_info as ExamModel;
use think\Request;
use think\Db;
use \think\File;


class Exam extends Base
{
    public function index()
    {
      $this -> isLogin(); 
      $this->view->assign(['title'=>'考试信息导入']);
      $this->view->assign(['keyword'=>'东华大学易班学霸思政题库']);
      $this->view->assign(['desc'=>'东华大学易班学霸思政题库']);
      return $this->view->fetch('exam');
    }

    public function input()
    {
      import('phpexcel.PHPExcel', EXTEND_PATH);
      $file = request()->file('exam');
      $info = $file->validate(['size'=>15678000000,'ext'=>'xlsx,xls,csv'])->move(ROOT_PATH . 'public' . DS . 'excel');
      if($info)
      {
        $exclePath = $info->getSaveName();  //获取文件名  
        $file_name = ROOT_PATH . 'public' . DS . 'excel' . DS . $exclePath;   //上传文件的地址  
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $PHPExcel = $objReader->load( $file_name);   //载入文件  
        $sheet = $PHPExcel->setActiveSheetIndex(0);         //设置第一个Sheet  
        //$data = $objPHPExcel->getActiveSheet()->getCell('A')->getValue();  //获取单元格A2的值  
        $highestRow = $sheet->getHighestRow();
        $highestColumm = $sheet->getHighestColumn();

        for($currentRow =2;$currentRow <= $highestRow;$currentRow++)
        {
         $school_id= $PHPExcel->getActiveSheet()->getCell("A".$currentRow)->getValue();//获取A列的值
         $teacher= $PHPExcel->getActiveSheet()->getCell("B".$currentRow)->getValue();//获取B列的值
         $class_id= $PHPExcel->getActiveSheet()->getCell("C".$currentRow)->getValue();//获取C列的值
         $course_id= $PHPExcel->getActiveSheet()->getCell("D".$currentRow)->getValue();//获取D列的值
         $exam_date= $PHPExcel->getActiveSheet()->getCell("E".$currentRow)->getValue();//获取E列的值
         $exam_time= $PHPExcel->getActiveSheet()->getCell("F".$currentRow)->getValue();//获取F列的值
         $exam_room= $PHPExcel->getActiveSheet()->getCell("G".$currentRow)->getValue();//获取G列的值
         
           $exam_data=array('student_id'=>$school_id,
                          'teacher'=>$teacher,
                          'class_id'=>$class_id,
                          'course'=>$course_id,
                          'exam_date'=>$exam_date,
                          'exam_time'=>$exam_time,
                          'exam_room'=>$exam_room,
                          
                );
          $stk=Db::table('exam_info')->insert($exam_data);
         if ($stk === null) {
             $this->error('导入考试信息失败，请重新导入，或联系管理员！','index.php/admin/exam/index');
               exit;
                }
            
        
      }
      $this->success('导入题库成功','index.php/admin/exam/index');

    }

    }
    public function output(){
      $this -> isLogin(); 
    	$this->view->assign(['title'=>'考试信息导入']);
      $this->view->assign(['keyword'=>'东华大学易班学霸思政题库']);
      $this->view->assign(['desc'=>'东华大学易班学霸思政题库']);

      $examList = ExamModel::paginate(10);
        //获取记录数量
        $count = ExamModel::count();

        //给结果集对象数组中的每个模板对象添加班级关联数据,非常重要
       

        $this -> view -> assign('examList', $examList);
        $this -> view -> assign('count', $count);
    	  return $this->view->fetch('output');
    }
}


?>