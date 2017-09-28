<?php

class HomeController extends BaseController {

   public function main($mn = '') {
        //echo $mn;
        if ($mn) {
            return View::make('pages.' . $mn)->with('mn', $mn);
        } else{
            return View::make('home')->with('mn', $mn);
        }
    }
    public function PieInfo($p,$m ) {
            return View::make('pages.pie'.$m)->with('p', $p);

    }
    public function ProInfo($pie,$pro,$m ) {
            return View::make('pages.pro'.$m)->with('pie', $pie)->with('pro', $pro);

    }

}
