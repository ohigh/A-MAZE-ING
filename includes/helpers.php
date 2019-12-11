<?php
	function buildTable($names, $data, $options = []){
		$html = '<div class"row">'; 
		$html .=  (isset($options['actions']['create'])) && $options['actions']['create'] !== '' ? '<div class="col s12 right"><a href="'.$options['actions']['create'].'" class="btn-floating btn-large waves-effect waves-light blue  right"><i class="material-icons">add</i></a></div>': '';
			$html .= '<div class="col s12">';
				$html .= (isset($options['class'])) && $options['class'] !== '' ? '<table class="'.$options['class'].'">' : '<table>';
					$html .= '<thead><tr>';
					for($i = 0; $i < sizeof($names); $i++){
						if(isset($options['actions']['selector']) && $options['actions']['selector'] != $names[$i]){
							$html .= '<th>'.$names[$i].'</th>';
						} else {
							$html .= '<th></th>';
						}
					}
					$html .= '</tr></thead>';
					$html .= '<tbody>';
					for($i = 0; $i < sizeof($data); $i++){
						$html .= '<tr>';
						foreach($data[$i] as $key => $value){
							// if {$value < '60> }
							if(isset($options['actions']['selector']) && $options['actions']['selector'] != $key){
								$html .= '<td class="admin-list">'.htmlspecialchars_decode(substr($value, 0, 100)).'</td>';
							} else {
								$html .= isset($options['actions']['edit']) && $options['actions']['edit'] !== '' ? '<td style="width: 20px;"><a href="'.$options['actions']['edit'].$value.'"><i class="material-icons">mode_edit</i></a></td>' : '';
								$html .= isset($options['actions']['view']) && $options['actions']['view'] !== '' ? '<td style="width: 20px;"><a href="'.$options['actions']['view'].$value.'"><i class="material-icons">visibility</i></a></td>' : '';
								$html .= isset($options['actions']['delete']) && $options['actions']['delete'] !== '' ? '<td style="width: 20px;"><a onclick="return(confirm(\'Er du sikker på at du vil slette?\'))" href="'.$options['actions']['delete'].$value.'"><i class="material-icons">delete</i></a></td>' : '';
							}
						}
						$html .= '</tr>';
					}
					$html .= '</tbody>';
				$html .= '</table>';
			$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

	function age() {
		
	}