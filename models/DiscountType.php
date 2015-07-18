<?php

namespace app\models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DiscountType
 *
 * @author dobro
 */
interface DiscountType {
    public function apply($json);
    public function form($view);    
}

