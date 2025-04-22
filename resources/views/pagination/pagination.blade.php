@php
  $page = $curPage;
@endphp
<?php
if ($recTotal > 0 && $pageSize > 0) {
  $total_links = ceil($recTotal / $pageSize);
  if ($total_links > 1) {
    $output = '<ul class="pagination pagination-sm justify-content-end">';
    $previous_link = '';
    $next_link = '';
    $page_link = '';
    $page_array = [];

    if ($total_links > 4) {
      if ($page < 5) {
        for ($count = 1; $count <= 5; $count++) {
          $page_array[] = $count;
        }
        $page_array[] = '...';
        $page_array[] = $total_links;
      } else {
        $end_limit = $total_links - 5;
        if ($page > $end_limit) {
          $page_array[] = 1;
          $page_array[] = '...';
          for ($count = $end_limit; $count <= $total_links; $count++) {
            $page_array[] = $count;
          }
        } else {
          $page_array[] = 1;
          $page_array[] = '...';
          for ($count = $page - 1; $count <= $page + 1; $count++) {
            $page_array[] = $count;
          }
          $page_array[] = '...';
          $page_array[] = $total_links;
        }
      }
    } else {
      for ($count = 1; $count <= $total_links; $count++) {
        $page_array[] = $count;
      }
    }

    for ($count = 0; $count < count($page_array); $count++) {
      $linkf = isset($filterAjax) ? $filterAjax : '';
      $is_active = ($curPage == $page_array[$count]) ? 'active' : '';

      if ($page == $page_array[$count]) {
        $linktype = "(" . $page_array[$count] . ",'" . $filterType . "','" . $url . "')";
        $page_link .= '
        <li class="page-item active">
          <a class="page-link" onclick="' . $linkf . $linktype . '" href="javascript:void(0)">' . $page_array[$count] . ' <span class="sr-only">(current)</span></a>
        </li>';

        // Previous Link
        $previous_id = $page_array[$count] - 1;
        if ($previous_id > 0) {
          $linktype = "(" . $previous_id . ",'" . $filterType . "','" . $url . "')";
          $previous_link = '<li class="page-item">
            <a class="page-link" href="javascript:void(0)" onclick="' . $linkf . $linktype . '"> Previous</a>
          </li>';
        } else {
          $previous_link = '<li class="page-item disabled">
            <a class="page-link" href="javascript:void(0)">Previous</a>
          </li>';
        }

        // Next Link
        $next_id = $page_array[$count] + 1;
        if ($next_id > $total_links) {
          $next_link = '<li class="page-item disabled">
            <a class="page-link" href="javascript:void(0)">Next </a>
          </li>';
        } else {
          $linktype = "(" . $next_id . ",'" . $filterType . "','" . $url . "')";
          $next_link = '<li class="page-item">
            <a class="page-link" href="javascript:void(0)" onclick="' . $linkf . $linktype . '" data-page_number="' . $next_id . '">Next </a>
          </li>';
        }
      } else {
        if ($page_array[$count] == '...') {
          $page_link .= '
          <li class="page-item disabled">
            <a class="page-link" href="javascript:void(0)">...</a>
          </li>';
        } else {
          $linktype = "(" . $page_array[$count] . ",'" . $filterType . "','" . $url . "')";
          $page_link .= '
          <li class="page-item ' . $is_active . '">
            <a class="page-link" onclick="' . $linkf . $linktype . '" href="javascript:void(0)" data-page_number="' . $page_array[$count] . '">' . $page_array[$count] . '</a>
          </li>';
        }
      }
    }

    $output .= $previous_link . $page_link . $next_link;
    $output .= '</ul>';
    echo $output;
  }
}
?>
