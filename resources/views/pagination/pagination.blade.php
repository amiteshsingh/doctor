@php
  $page = $curPage;
@endphp
<?php
  if($recTotal > 0 && $pageSize > 0){
    $total_links = ceil($recTotal/$pageSize);
    if($total_links > 1){
      $output = '<ul class="pagination pagination-sm no-margin pull-right">';
      $previous_link = '';
      $next_link = '';
      $page_link = '';
      if($total_links > 4)
      {
        if($page < 5)
        {
          for($count = 1; $count <= 5; $count++)
          {
            $page_array[] = $count;
          }
          $page_array[] = '...';
          $page_array[] = $total_links;
        }
        else
        {
          $end_limit = $total_links - 5;
          if($page > $end_limit)
          {
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $end_limit; $count <= $total_links; $count++)
            {
              $page_array[] = $count;
            }
          }
          else
          {
            $page_array[] = 1;
            $page_array[] = '...';
            for($count = $page - 1; $count <= $page + 1; $count++)
            {
              $page_array[] = $count;
            }
            $page_array[] = '...';
            $page_array[] = $total_links;
          }
        }
      }
      else
      {
        for($count = 1; $count <= $total_links; $count++)
        {
          $page_array[] = $count;
        }
      }
      for($count = 0; $count < count($page_array); $count++)
      {
        $activeClass = ($curPage==$page_array[$count])?'active':'';
        $linkf = isset($filterAjax)?$filterAjax:'';
        if($page == $page_array[$count])
        {
          
          $linktype = "(".$page_array[$count].",'".$filterType."','".$url."')";
          $page_link .= '
          <li>
            <a class="'.$activeClass.'" onclick="'.$linkf.$linktype.'" href="javascript:void(0)">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
          </li>
          ';
        
          $previous_id = $page_array[$count] - 1;
          if($previous_id > 0)
          {
            $linktype = "(".$previous_id.",'".$filterType."','".$url."')";
            $activeClass = ($curPage==$previous_id)?'active':'';
            $previous_link = '<li><a class="'.$activeClass.'" href="javascript:void(0)" onclick="'.$linkf.$linktype.'"><i class="fas fa-angle-double-left"></i> Previous</a></li>';
          }
          else
          {
            $previous_link = '
            <li>
              <a class="" href="javascript:void(0)"><i class="fas fa-angle-double-left"></i> Previous</a>
            </li>
            ';
          }
          $next_id = $page_array[$count] + 1;
          if($next_id > $total_links)
          {
            $next_link = '
            <li>
              <a class="" href="javascript:void(0)">Next <i class="fas fa-angle-double-right"></i></a>
            </li>
              ';
          }
          else
          {
            $linktype = "(".$next_id.",'".$filterType."','".$url."')";
            $activeClass = ($curPage==$next_id)?'active':'';
        
            $next_link = '<li><a class="'.$activeClass.'" href="javascript:void(0)" onclick="'.$linkf.$linktype.'" data-page_number="'.$next_id.'">Next <i class="fas fa-angle-double-right"></i></a></li>';
          }
        }
        else
        {
          if($page_array[$count] == '...')
          {
            $page_link .= '
            <li>
                <a class="" href="javascript:void(0)">...</a>
            </li>
            ';
          }
          else
          {
            $linkf = isset($filterAjax)?$filterAjax:'';
            $linktype = "(".$page_array[$count].",'".$filterType."','".$url."')";
            $page_link .= '
            <li><a class="'.$activeClass.'" onclick="'.$linkf.$linktype.'" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
            ';
          }
        }
      }
      $output .= $previous_link . $page_link . $next_link;
      $output .= '
      </ul>
      ';
      echo $output;
    }
  }
  ?>