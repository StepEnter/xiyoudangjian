<?php
 goto YIN7p; Z4SKR: $actenroll = pdo_fetchall("\x53\105\114\x45\103\x54\40\145\56\52\x2c\165\56\x72\145\141\x6c\x6e\x61\155\145\54\165\x2e\150\145\141\x64\160\151\143\x2c\141\x2e\x74\151\164\154\x65\40\106\x52\117\115\40" . tablename($this->table_actenroll) . "\x20\x65\x20\114\x45\106\124\x20\x4a\x4f\111\x4e\40" . tablename($this->table_user) . "\x20\165\40\x4f\116\40\145\56\x75\163\x65\162\x69\144\75\165\56\x69\144\x20\x4c\105\x46\x54\40\x4a\117\x49\116\40" . tablename($this->table_activity) . "\x20\x61\40\x4f\x4e\x20\145\x2e\x61\x63\164\151\166\151\x74\171\x69\144\75\x61\56\151\144\40\127\x48\x45\x52\105\x20\x65\x2e\x75\156\x69\x61\143\x69\144\75\x3a\165\156\x69\141\x63\x69\144\40\x4f\122\104\x45\122\x20\x42\131\x20\x65\x2e\x69\x64\40\x4c\x49\x4d\x49\124\40\62\60\40", array("\x3a\165\156\x69\x61\x63\x69\144" => $_W["\165\156\x69\x61\x63\151\144"])); goto S8o7u; T2n90: $this->result(0, '', array("\141\143\164\145\x6e\162\x6f\154\154" => $actenroll, "\x73\154\151\144\145" => $slide)); goto mi8kR; Og0w4: $psize = max(1, intval($_GPC["\160\x73\151\x7a\145"])); goto CcKdi; OlojP: x5i0w: goto uEI4i; YIN7p: global $_W, $_GPC; goto jt5CL; CcKdi: $list = pdo_fetchall("\123\105\114\105\103\x54\40\164\x61\x62\56\52\54\x62\x2e\156\x61\155\145\x20\106\x52\x4f\115\x20" . tablename($this->table_activity) . "\40\x74\x61\142\40\x4c\x45\106\124\x20\112\x4f\111\116\x20" . tablename($this->table_branch) . "\x20\142\x20\117\x4e\x20\x74\141\142\56\142\162\x61\156\x63\x68\151\144\75\x62\56\x69\144\x20\x57\110\105\x52\105\x20" . $par . "\x20\164\141\x62\x2e\x75\x6e\151\x61\143\x69\144\x3d\72\165\x6e\151\141\x63\x69\144\40\117\x52\x44\105\x52\40\102\x59\x20\x74\141\142\x2e\x70\x72\151\x6f\x72\x69\x74\x79\40\x44\x45\123\x43\54\x20\164\141\142\56\x69\144\40\x44\105\x53\103\40\x4c\x49\x4d\x49\124\40" . ($pindex - 1) * $psize . "\54" . $psize, array("\72\x75\x6e\x69\141\143\x69\144" => $_W["\165\156\151\x61\x63\151\x64"])); goto coAjf; LxJ5z: if ($op == "\x64\151\x73\x70\154\141\x79") { goto aiCIN; } goto Tx739; y7UpS: goto cxxks; goto VBIrt; coAjf: if (empty($list)) { goto eLc3x; } goto m4cq6; eMXv3: $this->result(0, '', $list); goto jsnVm; Tx739: if ($op == "\147\145\x74\x6d\157\162\x65") { goto uEK6O; } goto y7UpS; bYNlK: if (empty($actenroll)) { goto mc9A1; } goto gTRTv; nLrRb: J6B4c: goto M2pQD; FLPQs: $branchid = intval($_GPC["\x62\x72\x61\156\x63\150\x69\144"]); goto ce64Q; mi8kR: goto cxxks; goto Lz0IO; FE342: $par .= "\x20\164\141\142\x2e\x62\162\x61\156\x63\150\151\x64\x20\x69\x6e\x20\50\x20" . $branch["\163\143\157\162\x74"] . "\40\x29\x20\x41\x4e\104\40"; goto i1fnL; gTRTv: foreach ($actenroll as $k => $v) { $actenroll[$k]["\x68\145\x61\x64\x70\x69\x63"] = tomedia($v["\150\x65\141\144\160\151\143"]); jFYxb: } goto OlojP; S8o7u: $slide = pdo_fetchall("\123\x45\114\x45\x43\x54\x20\52\x20\x46\122\x4f\115\x20" . tablename($this->table_slide) . "\x20\127\110\x45\x52\105\x20\160\x6f\163\x69\x74\x69\157\x6e\x3d\47\141\143\164\x68\x6f\155\x65\x27\40\101\x4e\x44\x20\165\156\x69\141\143\x69\x64\x3d\72\165\x6e\151\141\143\x69\x64\x20\117\122\104\x45\x52\x20\102\131\40\x70\162\151\157\162\x69\164\x79\x20\104\105\x53\103\54\x20\151\144\40\x44\x45\123\x43", array("\x3a\165\x6e\x69\141\143\x69\144" => $_W["\x75\156\151\141\x63\x69\x64"])); goto bYNlK; ce64Q: $branch = pdo_get($this->table_branch, array("\151\x64" => $branchid, "\x75\x6e\x69\141\143\x69\144" => $_W["\165\156\x69\141\143\x69\144"])); goto BbTgx; uEI4i: mc9A1: goto wdxmn; Lz0IO: uEK6O: goto FLPQs; i1fnL: JZwPv: goto TB57B; BbTgx: $par = "\x20\x74\141\x62\56\163\x74\x61\164\x75\163\x20\111\x4e\40\50\62\x2c\63\x29\x20\101\x4e\104\x20"; goto Nmion; m4cq6: foreach ($list as $k => $v) { goto vDjV0; vDjV0: $list[$k]["\x74\151\154\x70\151\143"] = tomedia($v["\x74\x69\154\160\x69\143"]); goto Kgtyi; Vhc50: Jvpx3: goto jaUjM; Kgtyi: $list[$k]["\x63\162\145\141\x74\x65\x74\151\x6d\x65"] = date("\131\55\x6d\55\x64\40\110\72\151", $v["\x63\162\145\x61\x74\145\164\x69\x6d\145"]); goto Vhc50; jaUjM: } goto nLrRb; M2pQD: eLc3x: goto eMXv3; sgr0I: tL1gl: goto T2n90; jt5CL: $op = $_GPC["\157\160"] ? $_GPC["\157\160"] : "\144\151\x73\x70\x6c\x61\x79"; goto LxJ5z; VBIrt: aiCIN: goto Z4SKR; jO11C: foreach ($slide as $k => $v) { $slide[$k]["\164\x69\154\160\151\143"] = tomedia($v["\164\x69\x6c\160\151\143"]); GQ3oB: } goto nSBUj; nSBUj: U5sJT: goto sgr0I; TB57B: $pindex = max(1, intval($_GPC["\160\x69\x6e\144\x65\170"])); goto Og0w4; wdxmn: if (empty($slide)) { goto tL1gl; } goto jO11C; Nmion: if (empty($branch)) { goto JZwPv; } goto FE342; jsnVm: cxxks: