<?php

  /*
   *   CONFIGURAÇÃO DE URL DAS PÁGINAS
   */

   //Login - Usuário
   $config['url_login'] = "/usuario/entrar";

   //LOGOUT - Usuário
   $config['url_logout'] = "/usuario/sair";

   //CADASTRAR - Usuário
   $config['url_user_signin'] = "/usuario/cadastrar";

   //ALTERAR - Usuário
   $config['url_user_edit'] = "/usuario/editar";

   //DELETAR - Usuário
   $config['url_user_delete'] = "/usuario/deletar";

   //USUÁRIOS
   $config['url_users'] = "/sistema/usuarios";

   //PERFIL
   $config['url_perfil'] = "/usuario/perfil";

   //CONFIGURAÇÕES
   $config['url_configuracoes'] = "/sistema/configuracoes";

   //RELATÓRIOS
   $config['url_relatorios'] = "/sistema/gerarrelatorio";

   //DASHBOARD
   $config['url_dashboard'] = "/sistema/inicio";

   //frota
   $config['url_frota'] = "/frota";

   //estoque
   $config['url_estoque'] = "/estoque";

   //ENTRADAS - ESTOQUE
   $config['url_entradas'] = "/movimentacoes/entradas";

   //SAÍDAS - ESTOQUE
   $config['url_saidas'] = "/movimentacoes/saidas";

   //FORNECEDORES
   $config['url_fornecedores'] = "/fornecedores";

   //NOTAS & RECIBOS
   $config['url_notas'] = "/notas";

   //MANUTENÇÕES
   $config['url_manutencoes'] = "/manutencoes";

   //MANUTENÇÕES - PREVENTIVAS
   $config['url_preventivas'] = "/manutencoes/agendadas";

   //ATUALIZAÇÕES
   $config['url_atualizacoes'] = "/sistema/cadastros";

   //SOBRE
   $config['url_produtos'] = "/produtos";

   //AJUDA
   $config['url_ajuda'] = "/sistema/ajuda";

  /*
   *  OUTRAS CONFIGURAÇÕES
   */

   //COPYRIGHT
   $config['copyright'] = '© IWG Web Software. Todos os direitos reservados - 2019.';

   //VERSÃO DO sistema
   $config['versao'] = date('Ymd').' (beta)';

   $config['nivel_front'] =  100;
   $config['nivel_back'] =  100;
   $config['nivel_database'] = 100;
