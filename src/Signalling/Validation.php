<?php
/**
 * This file is part of the Universe package.
 *
 * @author Volkan Şengül <iletisim@volkansengul.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Universe\Signalling;

final class Validation
{
    private $input;
    private $data = [];
    private $ruleList = [];
    private $messages = [
        'required' => 'Lütfen {field} alanını boş bırakmayınız.',
        'email' => 'Geçerli bir e-posta adresi giriniz',
        'min' => 'Lütfen {field} alanına en az {rule_param} karakter giriniz',
        'max' => 'Lütfen {field} alanına en fazla {rule_param} karakter giriniz'
    ];
    private $labels = [];
    private $errors = [];

    public function __construct()
    {
        $this->input = new Signal();
    }

    public function rule($key, $rule, $param = null)
    {
        $this->ruleList[] = ['key'=>$key, 'rule' => $rule, 'param' => $param];
        $this->data[$key] = $this->input->$key;
        return $this;
    }

    public function rules($rules)
    {
        foreach ($rules as $k => $v) {
            foreach ($v as $_v) {
                $rule = $_v;
                $rule_param = null;
                if (strpos($_v, ':')) {
                    $_rule = explode(':', $_v);
                    $rule = $_rule[0];
                    $rule_param = $_rule[1];
                }
                $this->rule($k, $rule, $rule_param);
            }
        }
        return $this;
    }

    public function label($key, $label)
    {
        $this->labels[$key] = $label;
        return $this;
    }

    public function labels($labels)
    {
        foreach($labels as $k=>$v){
            $this->label($k,$v);
        }
        return $this;
    }

    public function message($key, $message)
    {
        $this->messages[$key] = $message;
        return $this;
    }

    public function messages($messages)
    {
        foreach($messages as $k=>$v){
            $this->message($k,$v);
        }
        return $this;
    }

    public function filter($key, $filter)
    {
        $this->filters[$key] = $filter;
        return $this;
    }

    public function filters($messages)
    {
        foreach($messages as $k=>$v){
            $this->message($k,$v);
        }
        return $this;
    }

    public function errors(){
        return $this->errors;
    }

    public function validate()
    {
        foreach ($this->ruleList as $k => $v) {
            if (method_exists($this, $v['rule'])) {
                $rule = $v['rule'];
                $key = $v['key'];
                if (!$this->$rule($this->input->$key, $v['param'])) {
                    $this->errors[$v['key']] = str_replace('{field}',$this->labels[$v['key']]??$v['key'],$this->messages[$rule]);
                }
            }
        }
        if (count($this->errors)>0){
            return false;
        } else {
            return true;
        }
    }




    public function required($value)
    {
        if ($value!==null && mb_strlen($value)>0){
            return true;
        } else {
            return false;
        }
    }

    public function email($value = null)
    {
        if ($value!==null){
            return true;
        } else {
            return false;
        }
    }

    public function min($param)
    {
        return true;
    }

    public function max($param)
    {
        return true;
    }

    public function uri($param)
    {
        return true;
    }

    public function regex($param)
    {
        return true;
    }
}

?>