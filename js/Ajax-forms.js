$(function(){

  /*
  *
  *  CÓDIGOS $/AJAX DE TODOS OS FORMULÁRIOS DO SISTEMA
  *
  */

  /* DECLARAÇÃO DE VARIÁVEIS */

  var base_url = 'http://189.1.163.64/mechawork';

  var counterms = setInterval('validaSessao()', 6000);

  var contadorNotify = 0;

  var tempo_logoff    = 5000; //  5 SEGUNDOS
  var tempo_logoff_s  = 60000;//  1 MINUTO
  var tempo_loading   = 15000;//  1/4 DE MINUTO
  var tempo_alerta_h  = 1000; //  1 SEGUNDO
  var tempo_alerta_m  = 1500; //  1 SEGUNDO E MEIO
  var tempo_alerta_s  = 2000; //  2 SEGUNDOS


  const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: tempo_alerta_m
  });

  // Garante que o usuário está habilitado e online para usar o sistema
  validaSessao = (function() {
      $.ajax({
        url: base_url + '/usuario/validar',
        dataType: 'json',
        success: function (data)
        {
            if(data.erro_nivel)
            {
              Swal.fire({
                title: 'Você será desconectado em 5 segundos...',
                type: 'error',
                timer: tempo_logoff,
                showConfirmButton: false,
                onClose: () => {
                  $.ajax({
                    url: base_url + '/usuario/sair',
                    success: function()
                    {
                      window.location.reload();
                    }
                  });
                }
              });
            }
            else if(data.error_pass)
            {
              Swal.fire({
                title: 'Você será desconectado em 5 segundos...',
                type: 'error',
                timer: tempo_logoff,
                showConfirmButton: false,
                onClose: () => {
                  $.ajax({
                    url: base_url + '/usuario/sair',
                    success: function()
                    {
                      window.location.reload();
                    }
                  });
                }
              });
            }
            else if(data.success == false)
            {

              Swal.fire({
                title: 'Ei! Ainda está aí?',
                text: 'Você será desconectado em 1 minuto se não responder!',
                type: 'warning',
                showCancelButton: false,
                showConfirmButton: true,
                confirmButtonText: 'Sim',
                allowOutsideClick: false,
                onBeforeOpen: () => {
                  clearInterval(counterms);
                },
                timer: tempo_logoff_s
              }).then((result) =>{
                if(result.value)
                {
                  $.ajax({
                    url: base_url + '/sistema/atividade',
                    success: function()
                    {
                      counterms = setInterval('validaSessao()', 6000);
                    }
                  });
                }
                else if(result.dismiss === Swal.DismissReason.timer)
                {
                  $.ajax({
                    url: base_url + '/usuario/sair',
                    async: true,
                    success: function()
                    {
                      window.location.reload();
                    }
                  });
                }
              })
            }
        }
      });
      return false;
    });

  $.ajax({
    url: base_url + '/sistema/geturlbase',
    dataType: 'json',
    success: function(data)
    {
      base_url = data.url_base;
    }
  });

  $.ajax({
    url: base_url + '/sistema/countnotify',
    dataType: 'json',
    success: function(data)
    {
      if(data.success)
      {
        if(parseInt(data.dados['count']) != contadorNotify)
        {
          contadorNotify = parseInt(data.dados['count']);
          var texto = '';
          if(contadorNotify == 1)
          {
            texto = 'Você tem '+data.dados['count']+' nova notificação!';
          }
          else
          {
            texto = 'Você tem '+data.dados['count']+' novas notificações!';
          }

          var dataHora = new Date();
          var localdate = dataHora.getDate() + '/' + (dataHora.getMonth()+1) + '/' + dataHora.getFullYear();

          $('#alerta_notify')
            .toast('show')
            .find('.titulo')
            .html('Aviso &nbsp &nbsp &nbsp');

          $('#alerta_notify')
            .find('.tempo')
            .text(localdate);

          $('#alerta_notify')
            .find('.toast-body')
            .text(texto);

          $('#btn-notify span').fadeTo(200, 1).text(contadorNotify);

        }
      }
      else
      {
        $('#alerta_notify').css('display', 'none');
      }
    }
  });

  $('#btn-notify .btn-notify-icon').on('mouseenter', function(){
    $(this).tooltip('hide');
  });


  $.ajax({
    url: base_url + '/usuario/atualizasessao'
  });

  $.ajax({
    url: base_url + '/sistema/cartdestroy'
  });

  // Atualiza atividade do usuário
  $('div').click(function(){
    $.ajax({
      url: base_url + '/sistema/atividade'
    });
  });

  // Atualiza atividade do usuário
  $('input').click(function(){
    $.ajax({
      url: base_url + '/sistema/atividade'
    });
  });

  // Atualiza atividade do usuário
  $('button').click(function(){
    $.ajax({
      url: base_url + '/sistema/atividade'
    });
  });

  // Formulário Login
	$('#form-login').submit(function(){

		var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/entrar',
      data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo excedido, tente novamente mais tarde!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false
            }).then((result) => {
              window.location.reload();
            })
          }
        })
      },

      success: function(data)
      {
        if(data.success)
        {
          window.location.reload();
        }
        else if(data.jaemuso)
        {
          Swal.fire({
            title: 'Acesso Negado!',
            text: 'Este usuário já está em uso!',
            type: 'error',
            timer: tempo_alerta_s,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          })
        }
        else
        {
          Swal.fire({
            title: 'Acesso Negado!',
            text: 'Usuário ou senha incorretos...',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          })
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Sair do sistema
  $('#sair').click(function(){

    Swal.fire({
      title: 'Deseja realmente sair?',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#074787',
      cancelButtonColor: '#ff0000',
      cancelButtonText: 'Não',
      confirmButtonText: 'Sim, quero sair!'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: base_url+'/Usuario/sair'
          }).done(function(){
            Toast.fire({
              type: 'success',
              title: 'Você saiu!',
              text: 'Até breve...',
              onClose: () =>
              {
                window.location.reload();
              }
            });
          });

          return false;

        }
      });


  });

  // Formulário editar perfil
	$('#form-profile').submit(function(){

		var dados = $(this).serialize();

		$.ajax({
			type: "POST",
			url: base_url + '/usuario/editar',
			data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo excedido, tente novamente mais tarde!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false
            }).then((result) => {
              window.location.reload();
            })
          }
        })
      },
      success: function(data)
      {
        if(data.success)
        {
      			Toast.fire({
      				title: 'Perfil editado com sucesso!',
      				type: 'success',
              onClose: () => {
                $.ajax({
                  url: base_url + '/usuario/atualizasessao'
                });

                window.location.reload();
              }
      			});
        }
        else
        {
      			Swal.fire({
      				title: 'Não foi possível alterar o seu perfil.',
      				type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
      			});
        }
      }
		}).fail(function(){
			Swal.fire({
				title: 'Ouve uma falha ao tentar editar o perfil!',
				type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      });
		});
		return false;
	});

  // Formulário adicionar produtos no carrinho - Manutenções Realizadas
  $('#form-addProduto').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: "POST",
      url: base_url + '/Manutencoes/AdicionaProduto',
      data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo excedido, tente novamente mais tarde!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false
            }).then((result) => {
              window.location.reload();
            })
          }
        })
      },
      success: function(data)
      {
        if(data.success)
        {
    			Toast.fire({
    				type: 'success',
    				title: 'Produto Adicionado!',
            onBeforeOpen: () => {

              htmlString = '';

              count = 0;

              $.each(data.produtos, function(i, item){
                count ++;
                htmlString +=
                 '<tr>'
                  +'<td>'+item.codigo+'</td>'
                  +'<td>'+item.descricao+'</td>'
                  +'<td>'+item.unidade+'</td>'
                  +'<td>'+item.qntd+'</td>'
                  +'<td>R$ '+item.vlr_unitario+'</td>'
                  +'<td>R$ '+item.vlr_total+'</td>'
                +'</tr>';
              });


              htmlString +=

                '<tr class="bg-light text-dark">'

                  +'<td colspan="5"><strong class="float-right">Total:</strong></td>'

                  +'<td>R$ '+data.total+'</td>'

                +'</tr>';

              $('#tbl_prod_usados_cadman tbody').html(htmlString);

              $('#ap_codproduto').removeClass('is-valid');
              $('#form-addProduto')[0].reset();
              $('#addProduto').modal('hide');
              $('#cartCount').val(count);
            }
          });
        }
        else if(data.semsaldo)
        {
    			Swal.fire({
    				type: 'warning',
    				title: 'Produto com estoque vazio!',
            timer: tempo_alerta_m,
            showConfirmButton: false
    			});
        }
        else if(data.saldoins)
        {
    			Swal.fire({
    				type: 'warning',
    				title: 'Produto sem saldo!',
            timer: tempo_alerta_m,
            showConfirmButton: false
    			});
        }
        else
        {
          Swal.fire({
            title: 'Não foi possível adicionar o produto!',
            type: 'warning',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      });
    });

    return false;
  });

  // Formulário adicionar produtos no carrinho - Entrada Estoque
  $('#form-addProdutoEntrada').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: "POST",
      url: base_url + '/estoque/adicionaproduto',
      data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          }
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Produto adicionado!',
            type: 'success',
            onClose: () => {

              htmlString = '';

              $.each(data.produtos, function(i, item){
                htmlString +=
                 '<tr>'
                  +'<td>'+item.codigo+'</td>'
                  +'<td>'+item.descricao+'</td>'
                  +'<td>'+item.unidade+'</td>'
                  +'<td>'+item.qntd+'</td>'
                  +'<td>R$ '+item.vlr_unitario+'</td>'
                  +'<td>R$ '+item.vlr_total+'</td>'
                +'</tr>';
              });

              htmlString +=

                '<tr class="bg-light text-dark">'

                  +'<td colspan="5"><strong class="float-right">Total:</strong></td>'

                  +'<td>R$ '+data.total+'</td>'

                +'</tr>';

              $('#tbl_prod_entrada_estoque tbody').html(htmlString);

              $('#ape_codproduto').removeClass('is-valid');
              $('#form-addProdutoEntrada')[0].reset();
              $('#addProdutoEntrada').modal('hide');

            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Ops... Produto não adicionado!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false
          }).then((result) => {
            window.location.reload();
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false
      }).then((result) => {
        window.location.reload();
      });
    });

    return false;
  });

  // Formulário remover produtos do carrinho
	$('#form-rmvProduto').submit(function(){

		var dados = $(this).serialize();

		$.ajax({
			type: 'POST',
			url: base_url + '/Manutencoes/removerProduto',
			data: dados,
			beforeSend: function(){
				Swal.fire({
					title: 'Carregando...',
					timer: tempo_loading,
					onBeforeOpen: () => {
						Swal.showLoading()
					}
				})
			}
		}).done(function(){
			Toast.fire({
				title: 'Produto(s) excluido(s) com sucesso!',
				type: 'success',
        onClose: () => {
          window.location.reload();
        }
			});
		}).fail(function(){
			Swal.fire({
				title: 'Houve uma falha ao tentar excluir o(s) produto(s)!',
				type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false
			}).then((result) => {
        window.location.reload();
			});
		});

		return false;
	});

  // Formulário de relatórios
  $('#form-relatorios').submit(function(){

		Swal.fire({
      title: 'Em Desenvolvimento!',
      text: 'Aguarde novas atualizações...',
      type: 'info'
    });

	});

  // Formulário cadastrar novo usuário
  $('#form-cadUsuario').submit(function(){

		var dados = $(this).serialize();

		$.ajax({
			type: "POST",
			url: base_url + '/Usuario/cadastrar',
			data: dados,
      dataType: "JSON",
			beforeSend: function ()
			{
					Swal.fire({
						title: 'Carregando...',
						timer: tempo_loading,
						onBeforeOpen: () => {
							Swal.showLoading();
						}
					})
			},
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
    				title: 'Cadastro Realizado!',
    				type: 'success',
            onClose: () => {
              window.location.reload();
            }
    			});
        }
        else if(data.email_error)
        {
          Swal.fire({
            title: 'Ops...',
            text: 'Os e-mails não conferem!',
            type: 'warning'
          })
        }
        else if(data.senha_error)
        {
          Swal.fire({
    				title: 'Ops...',
    				text: 'As senhas não conferem!',
    				type: 'warning'
    			})
        }
        else if(data.existe)
        {
          Swal.fire({
    				title: 'Ops...',
    				text: 'Esse e-mail já está sendo usado!',
    				type: 'warning'
    			})
        }
      }

		}).fail(function(){

			Swal.fire({
				title: 'Ops...',
				text: 'Houve uma falha inesperada!',
				type: 'error'
			})

		});

    return false;

	});

  // Preenche o modal com os dados do usuário - Edit User Account
  $('#verUsuarioModal').on('show.bs.modal',function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: "get",
      url: base_url + '/usuario/listar/' + dados,
      dataType:"JSON",
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          var nivel = data.dados['nivel'];

          var text = '';

          if(nivel == 1){ text = 'Administrador'}else{ text = 'Usuário'}

          $('#id_usuario').val(data.dados['id']);
          $('#edit_nome').val(data.dados['nome']);
          $('#edit_sobrenome').val(data.dados['sobrenome']);
          $('#edit_email').val(data.dados['email']);
          $('#edit_telefone').val(data.dados['telefone']);
          $('#edit_nivelatual').val(nivel).text(text);
        }
      }
    });

  });

  // Preenche o modal com os dados do usuário - Reset User Pass
  $('#resetUsuarioModal').on('show.bs.modal',function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');
    var senha = Math.floor(Math.random() * 99999 + 10000);

    $.ajax({
      type: "get",
      url: base_url + '/usuario/listar/' + dados,
      dataType:"JSON",
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#email').val(data.dados['email']);
          $('#senha').val(senha);
          $('#resetSenha_user').text(data.dados['nome']);
          $('#btnResetSenha_user').prop('disabled', true);
        }
      }
    });

  });

  // Formulário editar usuário
  $('#form-editUsuario').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: "POST",
      url: base_url + '/usuario/editar/',
      data: dados,
      dataType: "JSON",
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          }
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Cadastro atualizado com sucesso!',
            type: 'success',
            onClose: () => {
              $.ajax({
                url: base_url + '/usuario/atualizasessao'
              });

              window.location.reload();
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Não foi possível atualizar o cadastro!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      });
    });

    return false;

  });

  // Formulário restaurar senha
  $('#form-resetSenha').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/resetpass',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        $('#btnResetSenha_user').prop('disabled', true).text('Aguarde...');
      },
      success: function(data)
      {
        if(data.success)
        {

          Toast.fire({
            title: 'A senha do usuário foi restaurada!',
            type: 'success',
            onClose: () => {
              window.location.reload();
            }
          });
        }

        else
        {

          Swal.fire({

            title: 'Ops...',
            text: 'Não foi possível restaurar a senha!',
            type: 'error'

          }).then((result) => {

              window.location.reload();

          })
        }
      }
    }).fail(function(){

      Swal.fire({
        title: 'Ops...',
        text: 'Houve uma falha inesperada!',
        type: 'error'
      }).then((result) => {

          window.location.reload();

      })

    });

    return false;
  });

  // Formulário excluír usuário
  $('#form-excluirUser').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/deletar',
      data: dados,
      dataType: 'json',
      beforeSend: function ()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose:() => {
            Swal.fire({
              title: 'Ops...',
              text: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },

      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Usuário excluído!',
            type: 'success',
            onClose: () => {
              window.location.reload();
            }
          });
        }
        else if(data.emuso)
        {
            Swal.fire({
              title: 'Ops...',
              text: 'Você excluiu a si mesmo do sistema, e será desconectado em 5 segundos!',
              type: 'warning',
              timer: tempo_logoff,
              showConfirmButton: false,
              onClose: () => {
                $.ajax({
                  url: base_url + '/usuario/sair'
                });
              }
            });
        }
        else if(data.umadmin)
        {
            Swal.fire({
              title: 'Só existe um administrador!',
              type: 'warning',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
        }
        else
        {
            Swal.fire({
              title: 'Não foi possível excluir o usuário!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        });

    });

    return false;

  });

  // Retorna usuários excluídos
  $('#excluirUsuarioModal').on('show.bs.modal',function(event){

    var button = $(event.relatedTarget);
    var id = button.data('whatever');

    $.ajax({
      type: "get",
      url: base_url + '/usuario/listar/' + id,
      dataType:"JSON",
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#id_user_delete').val(data.dados['id']);
          $('#delete_user').text(data.dados['nome']);
          $('#btnDelete_user').prop('disabled', true);
        }
      }
    });
  });

  // Valida administrador - Delet User Account
  $('#userDeleteValida').change(function(){

    if($(this).val() == "")
    {
      $('#userDeleteValida').removeClass('is-valid').removeClass('is-invalid').val('');
      $('#btnDelete_user').prop('disabled', true);
    }

    var dados = {senha_atual: $(this).val()};

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/validar',
      data: dados,
      dataType: 'json',
      success: function(data)
      {
        if(data.success)
        {
          $('#userDeleteValida').removeClass('is-invalid').addClass('is-valid');
          $('#btnDelete_user').prop('disabled', false);
        }
        else
        {
          Swal.fire({
            title: 'Senha Incorreta!',
            type: 'error',
            showConfirmButton: false,
            timer: tempo_alerta_h
          }).then((result) => {
            $('#userDeleteValida').removeClass('is-valid').addClass('is-invalid').val('');
            $('#btnDelete_user').prop('disabled', true);
          });
        }
      }

    }).fail(function(){
      Swal.fire({
        title: 'Ops...',
        text: 'Houve uma falha inesperada!',
        type: 'error',
        showConfirmButton: false,
        timer: tempo_alerta_h
      })
    })

  });

  // Valida senha do administrador - Reset User Pass
  $('#resetPassValida').change(function(){

    var dados = {senha_atual: $(this).val()};

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/validar',
      data: dados,
      dataType: 'json',
      success: function(data)
      {
        if(data.success)
        {
          $('#resetPassValida').removeClass('is-invalid').addClass('is-valid');
          $('#btnResetSenha_user').prop('disabled', false);
        }
        else
        {
          Swal.fire({
            title: 'Senha Incorreta!',
            type: 'error',
            showConfirmButton: false,
            timer: tempo_alerta_h
          }).then((result) => {
            $('#resetPassValida').removeClass('is-valid').addClass('is-invalid');
            $('#btnResetSenha_user').prop('disabled', true);
          });
        }
      }

    }).fail(function(){
      Swal.fire({
        title: 'Ops...',
        text: 'Houve uma falha inesperada!',
        type: 'error',
        showConfirmButton: false,
        timer: tempo_alerta_h
      })
    })

  });

  // Restaura contas excluídas
  $('#form-removedUsers').submit(function(){

    var dados = $(this).serialize();


    $.ajax({
      type: 'post',
      url: base_url + '/usuario/restaurar',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onBeforeOpen: () => {
            Swal.showLoading();
          }
        }).then((result) =>{
          window.location.reload();
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Usuário(s) restaurado(s)!',
            type: 'success',
            onClose: () => {
              window.location.reload();
            }
          });
        }

        else
        {
          Swal.fire({
            title: 'Ops...',
            text: 'Não foi possível restaurar o(s) Usuário(s)!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false
          }).then((result) =>{
            window.location.reload();
          });
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false
        }).then((result) =>{
          window.location.reload();
        });

    });

    return false;

  });

  // Valida a senha - Perfil
  $('#perfil_cSenha').change(function(){

    var senha = $('#perfil_senha').val();
    var cSenha = $(this).val();

    if(senha != "")
    {
        $('#perfil_senha').removeClass('is-invalid');

        if(senha == cSenha)
        {

          $.ajax({
            type: 'post',
            url: base_url + '/usuario/validar',
            data: {senha_atual: senha},
            dataType: 'json',
            success: function(data)
            {
              if(data.success)
              {
                $('#perfil_senha').removeClass('is-invalid').addClass('is-valid');
                $('#perfil_cSenha').removeClass('is-invalid').addClass('is-valid');
                $('#btn-editar-perfil').prop('disabled', false);
              }
              else
              {
                $('#perfil_cSenha').val('');
                $('#perfil_senha').val('');
                $('#perfil_senha').addClass('is-invalid');
                $('#perfil_cSenha').addClass('is-invalid');
                $('#btn-editar-perfil').prop('disabled', true);
                Swal.fire({
                  title: 'Senha incorreta!',
                  text: 'Digite sua senha atual...',
                  timer: tempo_alerta_m,
                  showConfirmButton: false,
                  type: 'error'
                })
              }
            }
          })

        }
        else
        {
          $('#perfil_cSenha').val('');
          $('#perfil_senha').val('');
          Swal.fire({
            title: 'As senhas não conferem!',
            type: 'warning',
            timer: tempo_alerta_h,
            showConfirmButton: false
          })
        }

    }
    else
    {
      $('#perfil_senha').addClass('is-invalid');
      $('#btn-editar-perfil').prop('disabled', true);
      $('#perfil_cSenha').val('');
      $('#perfil_senha').val('');
    }

  });

  // Valida email - Perfil
  $('#user_email').change(function(){

    var email = $(this).val();
    var id = $('#id_usuario').val();

    $.ajax({
      type: 'get',
      url: base_url + '/usuario/listar/' + id,
      dataType: 'json',
      success: function(data)
      {
        if(data.dados['email'] == email)
        {
          $('#user_email').removeClass('is-invalid').addClass('is-valid');
        }
        else
        {
          $('#user_email').removeClass('is-valid').addClass('is-invalid').val('');
        }
      }
    });

  });

  // Formulário de alterar a senha - Perfil
  $('#form-alterarSenha').submit(function(){

    var dados = $(this).serialize();

      $.ajax({
        type: 'post',
        url: base_url + '/usuario/alterarsenha',
        data: dados,
        dataType: 'json',
        beforeSend: function ()
        {
          Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen: () => {
              Swal.showLoading();
            },
            onClose:() => {
              Swal.fire({
                title: 'Ops...',
                text: 'Tempo de execução excedido!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
            }
          });
        },
        success: function (data)
        {
          if(data.success)
          {
            Toast.fire({
              title: 'Senha Alterada!',
              type: 'success',
              onClose: () => {
                window.location.reload();
              }
            });
          }

          else if(data.error_pass)
          {
            Swal.fire({
              title: 'A nova senha não confere!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                $('#form-alterarSenha :input').removeClass('is-valid').removeClass('is-invalid');
                $('#editpass_cnovasenha').addClass('is-invalid').val('');
              }
            });
          }

          else if(data.error_validate)
          {
            Swal.fire({
              title: 'E-mail ou senha incorretos!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                $('#form-alterarSenha :input').removeClass('is-valid').removeClass('is-invalid');
                $('#editpass_email').addClass('is-invalid').val('');
                $('#editpass_senhaatual').addClass('is-invalid').val('');
              }
            });
          }

          else if(data.error_edit_pass)
          {
            Swal.fire({
              title: 'Não foi possível alterar a senha!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }

          else if(data.password_exists)
          {
            Swal.fire({
              title: 'Não é permitido usar senhas antigas!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                $('#form-alterarSenha :input').removeClass('is-valid').removeClass('is-invalid');
                $('#editpass_novasenha').addClass('is-invalid').val('');
                $('#editpass_cnovasenha').addClass('is-invalid').val('');
              }
            });
          }
        }
      }).fail(function(){
        Swal.fire({
          title: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        });
      });

      return false;

  });

  // Formulário de contato por e-mail - Login
  $('#form-emailcontato').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/sistema/atualizaemailcontato',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          }
        }).then((result) => {
          Swal.fire({
            title: 'Ops...',
            text: 'Tempo de execução excedido!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'E-mail de contato atualizado!',
            type: 'success',
            onClose: () => {
              window.location.reload();
            }
          });
        }

        else
        {
          Swal.fire({
            title:'Não foi possível atualizar o e-mail de contato!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false
          }).then((result) =>{
            window.location.reload();
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      });
    });

    return false;

  });

  // Formulário de configuração do tempo inativo
  $('#form-tmpinativo').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/sistema/atualizatempoinativo',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          }
        }).then((result) => {
          Swal.fire({
            title: 'Ops...',
            text: 'Tempo de execução excedido!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Tempo de inatividade atualizado!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }

        else
        {
          Swal.fire({
            title:'Não foi possível atualizar o tempo de inatividade!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false
          }).then((result) =>{
            window.location.reload();
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      });
    });

    return false;

  });

  // Formulário de cadastro das obras
  $('#form-cadObra').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/obras/cadastrar',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          }
        }).then((result) => {
          Swal.fire({
            title: 'Ops...',
            text: 'Tempo de execução excedido!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Obra Cadastrada!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }

        else
        {
          Swal.fire({
            title:'Não foi possível cadastrar a obra!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false
          }).then((result) =>{
            window.location.reload();
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      });
    });

    return false;

  });

  // Formulário de cadastro dos produtos
  $('#form-cadProdutos').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/produtos/cadastra',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {

        Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen:() => {
              Swal.showLoading();
            }
        }).then((result) => {
          Swal.fire({
            title: 'Tempo limite excedido!',
            timer: tempo_alerta_m,
            type: 'error',
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          })
        });

      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Produto Cadastrado!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Falha ao cadastarar o produto!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false
          }).then((result) =>{
            window.location.reload();
          });
        }
      }
    }).fail((result) => {

        Swal.fire({
          title: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false
        }).then((result) =>{
          window.location.reload();
        });
    });

    return false;
  });

  // Valida código do produto - Cadastro de Produto
  $('#form-cadProdutos').on('change focus', 'input[name=codigo]', function(){

    var codigo = $(this).val();

    if(codigo == "")
    {
      $('#form-cadProdutos').removeClass('was-validated');
      $('#form-cadProdutos input[name=codigo]').val('').removeClass('is-valid').removeClass('is-invalid');
      $('#btn-cadProdutos').text('Aguardando validação...').prop('disabled', true);
    }
    else if((codigo.length > 5)||(codigo.length < 5))
    {
      $('#form-cadProdutos').removeClass('was-validated');
      $('#form-cadProdutos input[name=codigo]').val('').removeClass('is-valid').addClass('is-invalid');
      $('#btn-cadProdutos').text('Aguardando validação...').prop('disabled', true);
    }
    else
    {
      $.ajax({
        url: base_url + '/produtos/validar/' + codigo,
        dataType: 'json',
        success: function(data)
        {
          if(data.success)
          {
            $('#form-cadProdutos').removeClass('was-validated');
            $('#form-cadProdutos input[name=codigo]').val('').removeClass('is-valid').addClass('is-invalid');
            $('#btn-cadProdutos').text('Aguardando validação...').prop('disabled', true);
          }
          else
          {
            $('#form-cadProdutos').addClass('was-validated');
            $('#form-cadProdutos input[name=codigo]').removeClass('is-invalid').addClass('is-valid');
            $('#btn-cadProdutos').text('Cadastrar Produto').prop('disabled', false);
          }
        }
      });

      return false;
    }

  });

  // Valida código do produto - Entrada Estoque
  $('#ape_codproduto').on("focus change", function(){

    var cod = $(this).val();

    if (cod == "")
    {
      $(this).removeClass('is-invalid').removeClass('is-valid').val('');
      $('#btn-addProdutoEntrada').prop('disabled', true);
    }
    else if(cod.length != 5)
    {
      Swal.fire({
        title: 'Digite um código com 5 caracteres!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false
      }).then((result) => {
        $(this).val('');
      });
    }
    else
    {
      $.ajax({
          url: base_url + '/produtos/validar/' + cod,
          dataType: 'json',
          success: function(data)
          {
            if(data.success)
            {
              $('#ape_codproduto').removeClass('is-invalid').addClass('is-valid');
              $('#btn-addProdutoEntrada').prop('disabled', false);
            }
            else
            {
              $('#ape_codproduto').removeClass('is-valid').addClass('is-invalid');
              $('#btn-addProdutoEntrada').prop('disabled', true);
            }
          }
      }).fail(function(){
        Swal.fire({
          title: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false
        }).then((result) => {
          $(this).val('');
        });
      });
    }


    return false;

  });

  // Valida código do produto - Entrada Estoque
  $('#ap_codproduto').on("focus change", function(){

    var cod = $(this).val();

    if (cod == "")
    {
      $(this).removeClass('is-invalid').removeClass('is-valid').val('');
      $('#btn-addProduto').prop('disabled', true);
    }
    else if(cod.length != 5)
    {
      Swal.fire({
        title: 'Digite um código com 5 caracteres!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false
      }).then((result) => {
        $(this).val('');
      });
    }
    else
    {
      $.ajax({
          url: base_url + '/produtos/validar/' + cod,
          dataType: 'json',
          success: function(data)
          {
            if(data.success)
            {
              $('#ap_codproduto').removeClass('is-invalid').addClass('is-valid');
              $('#form-addProduto [type=submit]').prop('disabled', false);
            }
            else
            {
              $('#ap_codproduto').removeClass('is-valid').addClass('is-invalid');
              $('#form-addProduto [type=submit]').prop('disabled', true);
            }
          }
      }).fail(function(){
        Swal.fire({
          title: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false
        }).then((result) => {
          $(this).val('');
        });
      });
    }


    return false;

  });

  // Valida placa do veículo/equipamento - Manutenção Preventiva
  $('#form_prev_placa').on("focus change", function(){

    if ($(this).val() == "")
    {
      $(this).removeClass('is-invalid').removeClass('is-valid').val('');
      $('#btn_formPrev').prop('disabled', true).text('Aguardando Validação');
    }
    else
    {
      var dados = { placa: $(this).val() };

      $.ajax({
          type: 'post',
          url: base_url + '/frota/validar/',
          data: dados,
          dataType: 'json',
          success: function(data)
          {
            if(data.success)
            {
              $('#form_prev_placa').removeClass('is-invalid').addClass('is-valid');
              $('#btn_formPrev').prop('disabled', false).text('Confirmar Agendamento');
            }
            else
            {
              $('#form_prev_placa').removeClass('is-valid').addClass('is-invalid');
              $('#btn_formPrev').prop('disabled', true).text('Aguardando Validação');
            }
          }
      }).fail(function(){
        Swal.fire({
          title: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false
        }).then((result) => {
          $(this).val('');
        });
      });


      return false;
    }

  });

  // Formulário de entrada de estoque
  $('#form-entradaEstoque').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/estoque/entrada',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              type: 'error',
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Entrada realizada!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Não foi possível realizar a entrada!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          })
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        })
    });

    return false;

  });

  // Realiza a pesquisa de estoque
  $('#pesquisa_estoque').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/estoque/pesquisar',
      data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data){

        if(data.success)
        {

          var htmlString = '';

          $.each(data.dados, function(i, item){

            htmlString +=
             '<tr>'
              +'<td>'+item.codigo+'</td>'
              +'<td>'+item.produto+'</td>'
              +'<td>'+item.qntd+'</td>'
              +'<td>'+item.unidade+'</td>'
              +'<td>R$ '+item.val_unitario+'</td>'
              +'<td>R$ '+item.val_total+'</td>'
              +'<td>'
                +'<div class="dropdown">'
                  +'<a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button"id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">Ações</a>'
                  +'<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">'
                    +'<a class="dropdown-item" href="#" data-toggle="modal" data-target="#historicoMoviModal" data-whatever="'+item.id_produto+'">Histórico</a>'
                  +'</div>'
                +'</div>'
              +'</td>'
            +'</tr>';

          });

          $('#estoquetable_body').html(htmlString);


          var titulo = '';

          if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
          else titulo = data.dados.length + ' Registro Encontrado!'

          Toast.fire({
            title: titulo,
            type: 'success'
          })
        }
        else
        {

          $('#estoquetable_body').html('<tr class="odd"><td valign="top" colspan="7" class="text-center">Nenhum registro encontrado</td></tr>');

          Swal.fire({
            title: 'Não há registros!',
            type: 'warning',
            timer: tempo_alerta_m,
            showConfirmButton: false
          })
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Popula tabelas do histórico de movimentações de um produto
  $('#historicoMoviModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/estoque/historico/' + dados,
      dataType: 'json',
      async: true,
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          var htmlStringEntrada = '';
          var htmlStringSaida = '';

          if((data.dados_entradas.length == 0)&&(data.dados_saidas.length == 0))
          {
            Swal.fire({
              title: 'Não há dados!',
              type: 'warning',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                $('#historicoMoviModal').modal('hide');
              }
            });
          }

          else
          {
            $.each(data.dados_entradas, function(i, item) {
                htmlStringEntrada +=
                '<tr>'
                  +'<th>'+item.id+'</th>'
                  +'<td>'+item.dt_entrada+'</td>'
                  +'<td>'+item.n_nota+'</td>'
                  +'<td>'+item.dt_nota+'</td>'
                  +'<td>'+item.qntd+'</td>'
                  +'<td>R$ '+item.vlr_unitario+'</td>'
                  +'<td>R$ '+item.vlr_total+'</td>'
                +'</tr>';
                $('#hs_codigo_label').text(item.codigo);
                $('#hs_descricao_label').text(item.descricao);
                $('#hs_unidade_label').text(item.unidade);
            });

            $.each(data.dados_saidas, function(i, item) {
                htmlStringSaida +=
                '<tr>'
                  +'<th>'+item.id+'</th>'
                  +'<td>'+item.data+'</td>'
                  +'<td>'+item.id_manutencao+'</td>'
                  +'<td>'+item.qnt+'</td>'
                  +'<td>R$ '+item.valor_unitario+'</td>'
                  +'<td>R$ '+item.total+'</td>'
                +'</tr>';
            });

            $('#hs_entradasproduto').html(htmlStringEntrada);
            $('#hs_saidasproduto').html(htmlStringSaida);

            Toast.fire({
              title: 'Dados Carregados!',
              type: 'success'
            });
          }
        }
      }
    });


  });

  validaCnpj = (function (cnpj) {

    cnpj = cnpj.replace(/[^\d]+/g,'');

    if(cnpj == '') return false;

    if (cnpj.length != 14)
        return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;

    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;

    return true;

});

  // Validação CNPJ do fornecedor - Entrada Estoque
  $('#cnpj_fornecedor').on("change", function(){

    var cnpj = $(this).val();

    if (cnpj == "")
    {
      $('#cnpj_fornecedor').val('').removeClass('is-valid').addClass('is-invalid');
      $('#razsoc_fornecedor').val('').removeClass('is-valid').prop('disabled', false);
      $('#telfon_fornecedor').val('').removeClass('is-valid').prop('disabled', false);
    }
    else if(!validaCnpj(cnpj))
    {
      $('#cnpj_fornecedor').val('').removeClass('is-valid').addClass('is-invalid');
      $('#razsoc_fornecedor').val('').removeClass('is-valid').prop('disabled', false);
      $('#telfon_fornecedor').val('').removeClass('is-valid').prop('disabled', false);
    }
    else
    {
      $.ajax({
        type: 'get',
        url: base_url + '/fornecedores/buscafornecedor/' + cnpj,
        dataType: 'json',
        success: function(data)
        {
          if(data.success)
          {
            $('#cnpj_fornecedor').removeClass('is-invalid').addClass('is-valid');
            $('#razsoc_fornecedor').val(data.dados['nome']).addClass('is-valid').prop('disabled', true);
            $('#telfon_fornecedor').val(data.dados['telefone']).addClass('is-valid').prop('disabled', true);
          }
          else if(data.cnpjblock)
          {
            $('#cnpj_fornecedor').val('').removeClass('is-valid').addClass('is-invalid');
            $('#razsoc_fornecedor').val('').removeClass('is-valid').prop('disabled', false);
            $('#telfon_fornecedor').val('').removeClass('is-valid').prop('disabled', false);
          }
          else
          {
            $('#cnpj_fornecedor').removeClass('is-invalid').addClass('is-valid');
            $('#razsoc_fornecedor').val('').removeClass('is-valid').prop('disabled', false);
            $('#telfon_fornecedor').val('').removeClass('is-valid').prop('disabled', false);
          }
        }
      });
    }

  });

  // Cadastro de Veículo/Equipamento
  $('#form-frota').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/frota/cadastrar',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen:() => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              type: 'error',
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Veículo/Equipamento Cadastrado!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Não foi possível cadastarar o Veículo/Equipamento!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false
          }).then((result) =>{
            window.location.reload();
          });
        }
      }
    }).fail((result) => {

      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false
      }).then((result) =>{
        window.location.reload();
      });
    });

    return false;
  });

  //Cadastro de Manutenção Preventiva
  $('#form-prev').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/manutencoes/cadprev',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen:() => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              type: 'error',
              showConfirmButton: false,
              onClose:()=>{
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Manutenção agendada!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Falha ao agendar a manutenção!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false
          }).then((result) =>{
            window.location.reload();
          });
        }
      }
    }).fail((result) => {

      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false
      }).then((result) =>{
        window.location.reload();
      });
    });

    return false;
  });

  // Formulário pesquisa de produtos
  $('#pesquisa_produto').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/produtos/pesquisar',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen:() => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              type: 'error',
              showConfirmButton: false,
              onClose:()=>{
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          var htmlString = '';

          $.each(data.dados, function(i, item){

            htmlString +=
             '<tr>'
              +'<td>'+item.codigo+'</td>'
              +'<td>'+item.descricao+'</td>'
              +'<td>'+item.unidade+'</td>'
            +'</tr>';

          });

          $('#produtostable_body').html(htmlString);

          var titulo = '';

          if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
          else titulo = data.dados.length + ' Registro Encontrado!'

          Toast.fire({
            title: titulo,
            type: 'success'
          })
        }
        else
        {

          $('#produtostable_body').html('<tr class="odd"><td valign="top" colspan="7" class="text-center">Nenhum registro encontrado</td></tr>');

          Swal.fire({
            title: 'Não há registros!',
            type: 'warning',
            timer: tempo_alerta_m,
            showConfirmButton: false
          })
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Formulário Cadastro de Manutenção
  $('#form-caMan').submit(function(){

    var dados = $(this).serialize();

    $.ajax({

    });

    return false;

  });

  // Modal Editar Obra
  $('#editarObraModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/obras/listar/' + dados,
      dataType: 'json',
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#form_editObra_id').val(data.dados[0]['id_obra']);
          $('#form_editObra_descricao').val(data.dados[0]['descricao']);
        }
      }
    });

  });

  // Modal Excluir Obra
  $('#excluirObraModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/obras/listar/' + dados,
      dataType: 'json',
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#id_obra_delete').val(data.dados[0]['id_obra']);
          $('#delete_obra').text(data.dados[0]['descricao']);
        }
      }
    });

  });

  // Formulário editar usuário
  $('#form-editarObra').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/obras/editar',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              type: 'error',
              showConfirmButton: false,
              onClose:()=>{
                window.location.reload();
              }
            });
          }
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Obra Atualizada!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Não foi possível atualizar o cadastro!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Formulário excluír usuário
  $('#form-excluirObra').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/obras/excluir',
      data: dados,
      dataType: 'json',
      beforeSend: function ()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose:() => {
            Swal.fire({
              title: 'Ops...',
              text: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },

      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Obra Excluída!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
            Swal.fire({
              title: 'Não foi possível excluir a obra!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        });

    });

    return false;

  });

  // Valida administrador - Deletar Obra
  $('#obraDeleteValida').change(function(){

    if($(this).val() == "")
    {
      $('#obraDeleteValida').removeClass('is-valid').removeClass('is-invalid').val('');
      $('#btnDelete_obra').prop('disabled', true);
    }

    var dados = {senha_atual: $(this).val()};

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/validar',
      data: dados,
      dataType: 'json',
      success: function(data)
      {
        if(data.success)
        {
          $('#obraDeleteValida').removeClass('is-invalid').addClass('is-valid');
          $('#btnDelete_obra').prop('disabled', false);
        }
        else
        {
          Swal.fire({
            title: 'Senha Incorreta!',
            type: 'error',
            showConfirmButton: false,
            timer: tempo_alerta_h
          }).then((result) => {
            $('#obraDeleteValida').removeClass('is-valid').addClass('is-invalid').val('');
            $('#btnDelete_obra').prop('disabled', true);
          });
        }
      }

    }).fail(function(){
      Swal.fire({
        title: 'Ops...',
        text: 'Houve uma falha inesperada!',
        type: 'error',
        showConfirmButton: false,
        timer: tempo_alerta_h
      })
    })

  });

  // Modal Editar Produto
  $('#editarProdutoModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/produtos/listar/' + dados,
      dataType: 'json',
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#form_editProduto_id').val(data.dados[0]['id_produto']);
          $('#form_editProduto_codigo').val(data.dados[0]['codigo']);
          $('#form_editProduto_descricao').val(data.dados[0]['descricao']);
          $('#form_editProduto_unidade').text(data.dados[0]['unidade']);
        }
      }
    });

  });

  // Modal Excluir Produto
  $('#excluirProdutoModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/produtos/listar/' + dados,
      dataType: 'json',
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#id_produto_delete').val(data.dados[0]['id_produto']);
          $('#delete_produto').text(data.dados[0]['codigo']);
        }
      }
    });

  });

  // Formulário editar Produto
  $('#form-editarProduto').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/produtos/editar',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              type: 'error',
              showConfirmButton: false,
              onClose:()=>{
                window.location.reload();
              }
            });
          }
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Produto Atualizado!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Não foi possível atualizar o produto!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Formulário excluír Produto
  $('#form-excluirProduto').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/produtos/excluir',
      data: dados,
      dataType: 'json',
      beforeSend: function ()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose:() => {
            Swal.fire({
              title: 'Ops...',
              text: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },

      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Produto Excluído!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
            Swal.fire({
              title: 'Não foi possível excluir o produto!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        });

    });

    return false;

  });

  // Valida administrador - Deletar Produto
  $('#produtoDeleteValida').change(function(){

    if($(this).val() == "")
    {
      $('#produtoDeleteValida').removeClass('is-valid').removeClass('is-invalid').val('');
      $('#btnDelete_produto').prop('disabled', true);
    }

    var dados = {senha_atual: $(this).val()};

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/validar',
      data: dados,
      dataType: 'json',
      success: function(data)
      {
        if(data.success)
        {
          $('#produtoDeleteValida').removeClass('is-invalid').addClass('is-valid');
          $('#btnDelete_produto').prop('disabled', false);
        }
        else
        {
          Swal.fire({
            title: 'Senha Incorreta!',
            type: 'error',
            showConfirmButton: false,
            timer: tempo_alerta_h
          }).then((result) => {
            $('#produtoDeleteValida').removeClass('is-valid').addClass('is-invalid').val('');
            $('#btnDelete_produto').prop('disabled', true);
          });
        }
      }

    }).fail(function(){
      Swal.fire({
        title: 'Ops...',
        text: 'Houve uma falha inesperada!',
        type: 'error',
        showConfirmButton: false,
        timer: tempo_alerta_h
      })
    })

  });

  // Modal Editar Frota
  $('#editarFrotaModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/frota/listar/' + dados,
      dataType: 'json',
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#form_editFrota_id').val(data.dados[0]['id_veiculo']);
          $('#form_editFrota_placa').val(data.dados[0]['placa']);
          $('#form_editFrota_descricao').val(data.dados[0]['descricao']);
          $('#form_editFrota_fabricante').val(data.dados[0]['fabricante']);
          $('#form_editFrota_ano').val(data.dados[0]['ano']);
          $('#form_editFrota_tipo').text(data.dados[0]['tipo']);
        }
      }
    });

  });

  // Modal Excluir Frota
  $('#excluirFrotaModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/frota/listar/' + dados,
      dataType: 'json',
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#id_frota_delete').val(data.dados[0]['id_veiculo']);
          $('#delete_frota').text(data.dados[0]['descricao']+' - '+data.dados[0]['placa']);
        }
      }
    });

  });

  // Formulário Editar Frota
  $('#form-editarFrota').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/frota/editar',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              type: 'error',
              showConfirmButton: false,
              onClose:()=>{
                window.location.reload();
              }
            });
          }
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Veículo/Equipamento Atualizado!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Não foi possível atualizar o produto!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Formulário excluír Frota
  $('#form-excluirFrota').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/frota/excluir',
      data: dados,
      dataType: 'json',
      beforeSend: function ()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose:() => {
            Swal.fire({
              title: 'Ops...',
              text: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },

      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Veículo/Equipamento Excluído!',
            type: 'success',
            onClose: () =>
            {
              window.location.reload();
            }
          });
        }
        else
        {
            Swal.fire({
              title: 'Não foi possível excluir o produto!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        });

    });

    return false;

  });

  // Valida administrador - Deletar Frota
  $('#frotaDeleteValida').change(function(){

    if($(this).val() == "")
    {
      $('#frotaDeleteValida').removeClass('is-valid').removeClass('is-invalid').val('');
      $('#btnDelete_frota').prop('disabled', true);
    }

    var dados = {senha_atual: $(this).val()};

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/validar',
      data: dados,
      dataType: 'json',
      success: function(data)
      {
        if(data.success)
        {
          $('#frotaDeleteValida').removeClass('is-invalid').addClass('is-valid');
          $('#btnDelete_frota').prop('disabled', false);
        }
        else
        {
          Swal.fire({
            title: 'Senha Incorreta!',
            type: 'error',
            showConfirmButton: false,
            timer: tempo_alerta_h
          }).then((result) => {
            $('#frotaDeleteValida').removeClass('is-valid').addClass('is-invalid').val('');
            $('#btnDelete_frota').prop('disabled', true);
          });
        }
      }

    }).fail(function(){
      Swal.fire({
        title: 'Ops...',
        text: 'Houve uma falha inesperada!',
        type: 'error',
        showConfirmButton: false,
        timer: tempo_alerta_h
      })
    })

  });

  $('#notify').on('mouseenter', 'a', function(){

    var dados = $(this).find('#id_notificacao').val();

    $.ajax({
      url: base_url + '/sistema/notificacaoVista/' + dados
    });

  });

  $('#form-cadMan-add-produto').tooltip({
    title: 'Adicione os produtos usados!',
    placement: 'right',
    trigger: 'toggle'
  });

  // Formulário Cadastro de Manutenção
  $('#form-cadMan').submit(function(){

    var dados = $(this).serialize();

    var tipo = $('#form-cadMan input[name=tipo]').val();

    var produtos = $('#cartCount').val();

    if((parseInt(produtos) > 0)&&(parseInt(tipo) == 1))
    {
      $.ajax({
        type: 'post',
        url: base_url + '/manutencoes/cadastra',
        data: dados,
        dataType: 'json',
        beforeSend: function ()
        {
          Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen: () => {
              Swal.showLoading();
            },
            onClose:() => {
              Swal.fire({
                title: 'Ops...',
                text: 'Tempo de execução excedido!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
            }
          });
        },

        success: function(data)
        {
          if(data.success)
          {
            Toast.fire({
              title: 'Manutenção Cadastrada!',
              type: 'success',
              onClose: () =>
              {
                window.location.reload();
              }
            });
          }
          else
          {
              Swal.fire({
                title: 'Não foi possível cadastrar a manutenção!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
          }
        }
      }).fail(function(){

          Swal.fire({
            title: 'Ops...',
            text: 'Houve uma falha inesperada!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });

      });

      return false;
    }
    else
    {
      $('#form-cadMan-add-produto').tooltip('show');
      return false;
    }

  });

  $('#form-cadMan').on('change focus', 'input[name=fornecedor]', function(){

    var dados = $(this).val();

    if(dados == "")
    {
        $('#form-cadMan input[name=fornecedor]').val('').removeClass('is-valid').removeClass('is-invalid');
        $('#form-cadMan input[name=razao-social]').val('').removeClass('is-valid').removeClass('is-invalid').prop('disabled', false);
        $('#form-cadMan input[name=telefone]').val('').removeClass('is-valid').removeClass('is-invalid').prop('disabled', false);
    }
    else
    {
      $.ajax({
        type: 'get',
        url: base_url + '/fornecedores/buscaFornecedor/' + dados,
        dataType: 'json',
        success: function(data)
        {
          if(data.success)
          {
            $('#form-cadMan input[name=razao-social]').val(data.dados['nome']).removeClass('is-invalid').addClass('is-valid').prop('disabled', true);
            $('#form-cadMan input[name=telefone]').val(data.dados['telefone']).removeClass('is-invalid').addClass('is-valid').prop('disabled', true);
          }
          else
          {
            $('#form-cadMan input[name=razao-social]').val('').removeClass('is-valid').addClass('is-invalid').prop('disabled', false);
            $('#form-cadMan input[name=telefone]').val('').removeClass('is-valid').addClass('is-invalid').prop('disabled', false);
          }
        }
      });

      return false;
    }

  });

  $('#form-frota').on('change focus', 'input[name=placa]', function(){

    var dados = $(this).val();

    if(dados == "")
    {
      $('#form-frota input[name=placa]').val('').removeClass('is-valid').removeClass('is-invalid');
      $('#form-frota button[type=submit]').prop('disabled', true).text('Aguardando a validação...');
    }
    else
    {
      $.ajax({
        type: 'post',
        url: base_url + '/Frota/validar/',
        data:{placa: dados},
        dataType: 'json',
        success: function(data)
        {
          if(data.success)
          {
            $('#form-frota input[name=placa]').val('').removeClass('is-valid').addClass('is-invalid');
            $('#form-frota button[type=submit]').prop('disabled', true).text('Aguardando a validação...');
          }
          else
          {
            $('#form-frota input[name=placa]').removeClass('is-invalid').addClass('is-valid');
            $('#form-frota button[type=submit]').prop('disabled', false).text('Cadastrar');
          }
        }
      });

      return false;
    }

  });

  $('#placaManutencao').on('change focus', function(){

    var dados = $(this).val();
    var tipo = $('#tipoManutencao').val();

    if(dados == "")
    {
      $('#placaManutencao').val('').removeClass('is-valid').removeClass('is-invalid');
			$('#btn-cadManI').prop('disabled', true).text('Aguardando a validação...');
			$('#btn-cadManE').prop('disabled', false).text('Aguardando a validação...');
    }
    else
    {
      $.ajax({
        type: 'post',
        url: base_url + '/Frota/validar/',
        data:{placa: dados},
        dataType: 'json',
        success: function(data)
        {
          if(data.success)
          {
            $('#placaManutencao').removeClass('is-invalid').addClass('is-valid');
            $('#form-cadMan input[name=placa]').val($('#placaManutencao').val());
            if(parseInt(tipo) == 1)
      			   $('#btn-cadManI').prop('disabled', false).text('Cadastrar Manutenção');

            else
      			   $('#btn-cadManE').prop('disabled', false).text('Cadastrar Manutenção');
          }
          else
          {
            $('#placaManutencao').val('').removeClass('is-valid').addClass('is-invalid');
      			$('#btn-cadManI').prop('disabled', true).text('Aguardando a validação...');
      			$('#btn-cadManE').prop('disabled', true).text('Aguardando a validação...');
          }
        }
      });

      return false;
    }

  });

  // Realiza a pesquisa de estoque
  $('#pesquisa_frota').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/frota/pesquisar',
      data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data){

        if(data.success)
        {

          var htmlString = '';

          $.each(data.dados, function(i, item){

            htmlString +=
             '<tr>'
              +'<td>'+item.placa+'</td>'
              +'<td>'+item.descricao+'</td>'
              +'<td>'+item.ano+'</td>'
              +'<td>'+item.fabricante+'</td>'
              +'<td>'+item.ult_man+'</td>'
              +'<td>'
                +'<div class="dropdown">'
                  +'<a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button"id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">Ações</a>'
                  +'<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">'
                    +'<a class="dropdown-item" href="#" data-toggle="modal" data-target="#historicoManFrota" data-whatever="'+item.placa+'">Histórico</a>'
                  +'</div>'
                +'</div>'
              +'</td>'
            +'</tr>';

          });

          $('#tbl_frota_body').html(htmlString);


          var titulo = '';

          if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
          else titulo = data.dados.length + ' Registro Encontrado!'

          Toast.fire({
            title: titulo,
            type: 'success'
          })
        }
        else
        {

          $('#tbl_frota_body').html('<tr class="odd"><td valign="top" colspan="7" class="text-center">Nenhum registro encontrado</td></tr>');

          Swal.fire({
            title: 'Não há registros!',
            type: 'warning',
            timer: tempo_alerta_m,
            showConfirmButton: false
          })
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Popula tabelas do histórico de manutenções da frota
  $('#historicoManFrota').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/frota/historico/' + dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          var htmlString = '';

          $.each(data.dados, function(i, item) {

              var nota = '';
              var custo_i = '';

              if(item.nota == null){nota = '<span class="badge badge-primary">Manutenção Interna</span>';}else{nota = item.nota;}
              if(parseInt(item.custo_i) < 1){custo_i = '<span class="badge badge-primary">Manutenção Externa</span>';}else{custo_i = 'R$ '+item.custo_i;}

              htmlString +=
              '<tr>'
                +'<th>'+item.id+'</th>'
                +'<td>'+item.hr_inicio+'</td>'
                +'<td>'+item.hr_fim+'</td>'
                +'<td>'+item.ht+'</td>'
                +'<td>'+item.descricao+'</td>'
                +'<td>'+nota+'</td>'
                +'<td>'+custo_i+'</td>'
                +'<td>'+item.obra+'</td>'
                +'<td>'+item.email+'</td>'
              +'</tr>';

              $('#hs_placa_label').text(item.placa);
              $('#hs_descricao_label').text(item.nome);
              $('#hs_ano_label').text(item.ano);
          });

          $('#tbl_hsmanfrota tbody').html(htmlString);

          Toast.fire({
            title: 'Dados Carregados!',
            type: 'success'
          })
        }
        else
        {
          Swal.fire({
            title: 'Não há registros de manutenções!',
            timer: tempo_alerta_s,
            showConfirmButton: false,
            type: 'warning',
            onClose: () => {
              $('#historicoManFrota').modal('hide');
            }
          })
        }
      },
      error: function(){
        Swal.fire({
          title: 'Houve uma falha inesperada!',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          type: 'error'
        })
      }
    });
  });

  $('#gerarReltorio').submit(function(){

    var dados = $(this).serialize();

    window.open(base_url + '/sistema/relatorios?' + dados);

  });

  // Realiza a pesquisa de estoque
  $('#pesquisa_fornecedores').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/fornecedores/pesquisar',
      data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data){

        if(data.success)
        {

          var htmlString = '';

          if(parseInt(data.user_lv) == 1)
          {
            $.each(data.dados, function(i, item){

              if(parseInt(item.estatus) == 1)
              {
                htmlString +=
                 '<tr class="text-success">'
                  +'<td><h6><i data-feather="check-circle"></i></h6></td>'
                  +'<td>'+item.cnpj+'</td>'
                  +'<td>'+item.razao_social+'</td>'
                  +'<td>'+item.telefone+'</td>'
                  +'<td>'
                    +'<div class="dropdown">'
                      +'<a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button"id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">Ações</a>'
                      +'<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">'
                        +'<a class="dropdown-item" href="#" data-toggle="modal" data-target="#historicoFornecedorModal" data-id="'+item.id+'" data-cnpj="'+item.cnpj+'" data-nome="'+item.razao_social+'" data-telefone="'+item.telefone+'">Histórico</a>'
                        +'<a class="dropdown-item" href="#" data-toggle="modal" data-target="#editarFornecedorModal" data-id="'+item.id+'" data-cnpj="'+item.cnpj+'" data-nome="'+item.razao_social+'" data-telefone="'+item.telefone+'">Editar</a>'
                        +'<div class="dropdown-divider"></div>'
                        +'<a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#excluirFornecedorModal" data-id="'+item.id+'" data-cnpj="'+item.cnpj+'" data-nome="'+item.razao_social+'" data-telefone="'+item.telefone+'">Excluir</a>'
                      +'</div>'
                    +'</div>'
                  +'</td>'
                +'</tr>';
              }
              else
              {
                htmlString +=
                 '<tr class="text-danger">'
                  +'<td><h6><i data-feather="x-circle"></i></h6></td>'
                  +'<td>'+item.cnpj+'</td>'
                  +'<td>'+item.razao_social+'</td>'
                  +'<td>'+item.telefone+'</td>'
                  +'<td><button class="btn btn-sm btn-outline-primary" title="Restaurar" data-toggle="modal" data-target="#restauraFornecedorModal" data-id="'+item.id+'" data-cnpj="'+item.cnpj+'" data-nome="'+item.razao_social+'" data-telefone="'+item.telefone+'"><i data-feather="refresh-ccw"></i></button></td>'
                +'</tr>';
              }

            });
          }
          else
          {
            $.each(data.dados, function(i, item){

              if(parseInt(item.estatus) == 1)
              {
                htmlString +=
                 '<tr class="text-success">'
                  +'<td><h6><i data-feather="check-circle"></i></h6></td>'
                  +'<td>'+item.cnpj+'</td>'
                  +'<td>'+item.razao_social+'</td>'
                  +'<td>'+item.telefone+'</td>'
                  +'<td>'
                    +'<div class="dropdown">'
                      +'<a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button"id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">Ações</a>'
                      +'<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">'
                        +'<a class="dropdown-item" href="#" data-toggle="modal" data-target="#historicoFornecedorModal" data-id="'+item.id+'" data-cnpj="'+item.cnpj+'" data-nome="'+item.razao_social+'" data-telefone="'+item.telefone+'">Histórico</a>'
                        +'<a class="dropdown-item" href="#" data-toggle="modal" data-target="#editarFornecedorModal" data-id="'+item.id+'" data-cnpj="'+item.cnpj+'" data-nome="'+item.razao_social+'" data-telefone="'+item.telefone+'">Editar</a>'
                      +'</div>'
                    +'</div>'
                  +'</td>'
                +'</tr>';
              }
              else
              {
                htmlString +=
                 '<tr class="text-danger">'
                  +'<td><h6><i data-feather="x-circle"></i></h6></td>'
                  +'<td>'+item.cnpj+'</td>'
                  +'<td>'+item.razao_social+'</td>'
                  +'<td>'+item.telefone+'</td>'
                  +'<td><button class="btn btn-sm btn-outline-primary" title="Restaurar"><i data-feather="refresh-ccw"></i></button></td>'
                +'</tr>';
              }

            });
          }

          $('#tabela_fornecedores tbody').html(htmlString);

          feather.replace();

          var titulo = '';

          if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
          else titulo = data.dados.length + ' Registro Encontrado!'

          Toast.fire({
            title: titulo,
            type: 'success'
          })
        }
        else
        {

          $('#tabela_fornecedores tbody').html('<tr class="odd"><td valign="top" colspan="7" class="text-center">Nenhum registro encontrado</td></tr>');

          Swal.fire({
            title: 'Não há registros!',
            type: 'warning',
            timer: tempo_alerta_m,
            showConfirmButton: false
          })
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Realiza a pesquisa de estoque
  $('#pesquisa_entradas').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/movimentacoes/pesquisa_entrada',
      data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data){

        if(data.success)
        {

          var htmlString = '';

          $.each(data.dados, function(i, item){

            if(item.situacao >= 1)
            {

              htmlString +=
               '<tr>'
               +'<td>'
                 +'<span class="text-success"><i data-feather="check-circle"></i></span>'
               +'</td>'
                +'<td>'+item.id_entrada+'</td>'
                +'<td>'+item.data+'</td>'
                +'<td>'+item.cod+'</td>'
                +'<td>'+item.descricao+'</td>'
                +'<td>'+item.nota+'</td>'
                +'<td>'+item.qty+'</td>'
                +'<td>R$ '+item.vlr_unitario+'</td>'
                +'<td>R$ '+item.vlr_total+'</td>'
              +'</tr>';

            }
            else
            {

              htmlString +=
               '<tr class="text-secondary">'
               +'<td>'
                 +'<span class="text-danger"><i data-feather="x-circle"></i></span>'
               +'</td>'
                +'<td>'+item.id_entrada+'</td>'
                +'<td>'+item.data+'</td>'
                +'<td>'+item.cod+'</td>'
                +'<td>'+item.descricao+'</td>'
                +'<td>'+item.nota+'</td>'
                +'<td>'+item.qty+'</td>'
                +'<td>R$ '+item.vlr_unitario+'</td>'
                +'<td>R$ '+item.vlr_total+'</td>'
              +'</tr>';

            }

          });

          $('#tabela_entradas tbody').html(htmlString);

          feather.replace();

          var titulo = '';

          if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
          else titulo = data.dados.length + ' Registro Encontrado!'

          Toast.fire({
            title: titulo,
            type: 'success'
          })
        }
        else
        {

          $('#tabela_entradas tbody').html('<tr class="odd"><td valign="top" colspan="8" class="text-center">Nenhum registro encontrado</td></tr>');

          Swal.fire({
            title: 'Não há registros!',
            type: 'warning',
            timer: tempo_alerta_m,
            showConfirmButton: false
          })
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Realiza a pesquisa de estoque
  $('#pesquisa_saidas').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/movimentacoes/pesquisa_saida',
      data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data){

        if(data.success)
        {

          var htmlString = '';

          $.each(data.dados, function(i, item){

            if(item.situacao >= 1)
            {
              htmlString +=
               '<tr>'
               +'<td>'
                 +'<span class="text-success"><i data-feather="check-circle"></i></span>'
               +'</td>'
                +'<td>'+item.id_saida+'</td>'
                +'<td>'+item.data+'</td>'
                +'<td>'+item.cod+'</td>'
                +'<td>'+item.descricao+'</td>'
                +'<td>'+item.qty+'</td>'
                +'<td>R$ '+item.vlr_unitario+'</td>'
                +'<td>R$ '+item.vlr_total+'</td>'
                +'<td></td>'
              +'</tr>';
            }
            else
            {
              htmlString +=
               '<tr class="text-secondary">'
               +'<td>'
                 +'<span class="text-danger"><i data-feather="x-circle"></i></span>'
               +'</td>'
                +'<td>'+item.id_saida+'</td>'
                +'<td>'+item.data+'</td>'
                +'<td>'+item.cod+'</td>'
                +'<td>'+item.descricao+'</td>'
                +'<td>'+item.qty+'</td>'
                +'<td>R$ '+item.vlr_unitario+'</td>'
                +'<td>R$ '+item.vlr_total+'</td>'
                +'<td></td>'
              +'</tr>';
            }

          });

          $('#tabela_saidas tbody').html(htmlString);

          feather.replace();

          var titulo = '';

          if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
          else titulo = data.dados.length + ' Registro Encontrado!'

          Toast.fire({
            title: titulo,
            type: 'success'
          })
        }
        else
        {

          $('#tabela_saidas tbody').html('<tr class="odd"><td valign="top" colspan="9" class="text-center">Nenhum registro encontrado</td></tr>');

          Swal.fire({
            title: 'Não há registros!',
            type: 'warning',
            timer: tempo_alerta_m,
            showConfirmButton: false
          })
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Realiza a pesquisa de estoque
  $('#pesquisa_manutencoes').submit(function(){

      var dados = $(this).serialize();

      $.ajax({
        type: 'post',
        url: base_url + '/manutencoes/pesquisa_realizadas',
        data: dados,
        dataType: 'json',
        beforeSend: function(){
          Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen: () => {
              Swal.showLoading();
            },
            onClose: () => {
              Swal.fire({
                title: 'Tempo de execução excedido!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
            }
          });
        },
        success: function(data){

          if(data.success)
          {

            var htmlString = '';

            $.each(data.dados, function(i, item){

              var nota = '';
              var custo_i = '';

              if(item.nota == null){nota = '<span class="badge badge-primary">Manutenção Interna</span>';}else{nota = item.nota;}
              if(parseInt(item.custo_i) < 1){custo_i = '<span class="badge badge-primary">Manutenção Externa</span>';}else{custo_i = 'R$ '+item.custo_i;}

              if(item.situacao >= 1)
              {
                htmlString +=
                 '<tr>'
                 +'<td>'
                   +'<span class="text-success"><i data-feather="check-circle"></i></span>'
                 +'</td>'
                  +'<td>'+item.placa+'</td>'
                  +'<td>'+item.descricao+'</td>'
                  +'<td>'+item.inicio+'</td>'
                  +'<td>'+item.termino+'</td>'
                  +'<td>'+nota+'</td>'
                  +'<td>'+custo_i+'</td>'
                  +'<td>'
                    +'<div class="dropdown">'
                      +'<a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button"id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">Ações</a>'
                      +'<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">'
                        +'<a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#estornarManutencaoModal" data-id="'+item.id_manutencao+'" data-placa="'+item.placa+'" data-inicio="'+item.inicio+'" data-termino="'+item.termino+'">Estornar</a>'
                      +'</div>'
                    +'</div>'
                  +'</td>'
                +'</tr>';
              }
              else
              {
                htmlString +=
                 '<tr class="text-secondary">'
                 +'<td>'
                   +'<span class="text-danger"><i data-feather="x-circle"></i></span>'
                 +'</td>'
                  +'<td>'+item.placa+'</td>'
                  +'<td>'+item.descricao+'</td>'
                  +'<td>'+item.inicio+'</td>'
                  +'<td>'+item.termino+'</td>'
                  +'<td>'+nota+'</td>'
                  +'<td>R$ '+custo_i+'</td>'
                  +'<td></td>'
                +'</tr>';
              }

            });

            $('#tabela_man_realizadas tbody').html(htmlString);

            feather.replace();

            var titulo = '';

            if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
            else titulo = data.dados.length + ' Registro Encontrado!'

            Toast.fire({
              title: titulo,
              type: 'success'
            })
          }
          else
          {

            $('#tabela_man_realizadas tbody').html('<tr class="odd"><td valign="top" colspan="9" class="text-center">Nenhum registro encontrado</td></tr>');

            Swal.fire({
              title: 'Não há registros!',
              type: 'warning',
              timer: tempo_alerta_m,
              showConfirmButton: false
            })
          }
        }
      }).fail(function(){
        Swal.fire({
          title: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        })
      });

      return false;

    });

  // Modal Editar Frota
  $('#editarAgendamentoModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/manutencoes/lista_agendamento/' + dados,
      dataType: 'json',
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#form-editarAgendamento input[name=id_futura]').val(data.dados[0]['id_futura']);
          $('#form_editAgendamento_placa').val(data.dados[0]['placa_numero']);
          $('#form-editarAgendamento input[name=descricao]').val(data.dados[0]['descricao']);
          $('#form-editarAgendamento input[name=data-minima]').val(data.dados[0]['data_minima']);
          $('#form-editarAgendamento input[name=data-prevista]').val(data.dados[0]['data_prevista']);
          $('#form-editarAgendamento input[name=km]').val(data.dados[0]['quilometragem']);
        }
      }
    });

  });

  // Modal Excluir Frota
  $('#excluirAgendamentoModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/manutencoes/lista_agendamento/' + dados,
      dataType: 'json',
      async: true,
      success: function(d)
      {
        if(d.success)
        {

          var data = new Date(d.dados[0]['data_prevista']);
          var dataFormatada = [data.getDate(), data.getMonth() + 1, data.getFullYear()].join('/');

          $('#id_agendamento_delete').val(d.dados[0]['id_futura']);
          $('#delete_agendamento').text(dataFormatada+' do '+d.dados[0]['descricao']+' - '+d.dados[0]['placa_numero']);
        }
      }
    });

  });

  // Formulário Editar Frota
  $('#form-editarAgendamento').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/manutencoes/edita_agendamento',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              type: 'error',
              showConfirmButton: false,
              onClose:()=>{
                window.location.reload();
              }
            });
          }
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Toast.fire({
            title: 'Agendamento Atualizado!',
            type: 'success',
            onClose: () => {
              window.location.reload();
            }
          });
        }
        else if(data.aeerror)
        {
          Swal.fire({
            title: 'Campos Vazios!',
            text: 'Alerta Antecipado',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              $('#form-editarAgendamento input[name=data-minima]').val('').addClass('is-invalid');
              $('#form-editarAgendamento input[name=km]').val('').addClass('is-invalid');
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Não foi possível atualizar o agendamento!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Formulário excluír Frota
  $('#form-excluirAgendamento').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/manutencoes/excluir_agendada',
      data: dados,
      dataType: 'json',
      beforeSend: function ()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose:() => {
            Swal.fire({
              title: 'Ops...',
              text: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          Swal.fire({
            title: 'Agendamento Excluído!',
            type: 'success',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
        else
        {
            Swal.fire({
              title: 'Não foi possível excluir o agendamento!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        });

    });

    return false;

  });

  // Valida administrador - Deletar Frota
  $('#agendamentoDeleteValida').change(function(){

    if($(this).val() == "")
    {
      $('#agendamentoDeleteValida').removeClass('is-valid').removeClass('is-invalid').val('');
      $('#btnDelete_agendamento').prop('disabled', true);
    }

    var dados = {senha_atual: $(this).val()};

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/validar',
      data: dados,
      dataType: 'json',
      success: function(data)
      {
        if(data.success)
        {
          $('#agendamentoDeleteValida').removeClass('is-invalid').addClass('is-valid');
          $('#btnDelete_agendamento').prop('disabled', false);
        }
        else
        {
          Swal.fire({
            title: 'Senha Incorreta!',
            type: 'error',
            showConfirmButton: false,
            timer: tempo_alerta_h,
            onClose: () => {
              $('#agendamentoDeleteValida').removeClass('is-valid').addClass('is-invalid').val('');
              $('#btnDelete_agendamento').prop('disabled', true);
            }
          });
        }
      }

    }).fail(function(){
      Swal.fire({
        title: 'Ops...',
        text: 'Houve uma falha inesperada!',
        type: 'error',
        showConfirmButton: false,
        timer: tempo_alerta_h
      })
    })

  });

  // Modal Excluir Frota
  $('#estornarEntradaModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var dados = button.data('whatever');

    $.ajax({
      type: 'get',
      url: base_url + '/movimentacoes/lista_entrada/' + dados,
      dataType: 'json',
      async: true,
      success: function(data)
      {
        if(data.success)
        {
          $('#id_entrada_estorna').val(data.dados[0]['id_entrada']);
          $('#estorna_entrada').html('<br> Data: '+data.dados[0]['data']+'<br> Produto: '+data.dados[0]['cod']+' - '+data.dados[0]['descricao']+'<br> Valor Total: R$ '+data.dados[0]['vlr_total']);
        }
      }
    });

  });

  // Formulário excluír Frota
  $('#form-estornarEntrada').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/movimentacoes/estorna_entrada',
      data: dados,
      dataType: 'json',
      beforeSend: function ()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose:() => {
            Swal.fire({
              title: 'Ops...',
              text: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          Swal.fire({
            title: 'Entrada Estornada!',
            type: 'success',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
        else
        {
            Swal.fire({
              title: 'Não foi possível estornar a entrada!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        });

    });

    return false;

  });

  // Valida administrador - Deletar Frota
  $('#entradaEstornaValida').change(function(){

    if($(this).val() == "")
    {
      $('#entradaEstornaValida').removeClass('is-valid').removeClass('is-invalid').val('');
      $('#btnEstorna_entrada').prop('disabled', true);
    }
    else
    {
      var dados = {senha_atual: $(this).val()};

      $.ajax({
        type: 'post',
        url: base_url + '/usuario/validar',
        data: dados,
        dataType: 'json',
        success: function(data)
        {
          if(data.success)
          {
            $('#entradaEstornaValida').removeClass('is-invalid').addClass('is-valid');
            $('#btnEstorna_entrada').prop('disabled', false);
          }
          else
          {
            Swal.fire({
              title: 'Senha Incorreta!',
              type: 'error',
              showConfirmButton: false,
              timer: tempo_alerta_h,
              onClose: () => {
                $('#entradaEstornaValida').removeClass('is-valid').addClass('is-invalid').val('');
                $('#btnEstorna_entrada').prop('disabled', true);
              }
            });
          }
        }

      }).fail(function(){
        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          showConfirmButton: false,
          timer: tempo_alerta_h
        })
      })

      return false;
    }


  });

  // Realiza a pesquisa de notas & recibos
  $('#pesquisa_notas').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/notas/pesquisar',
      data: dados,
      dataType: 'json',
      beforeSend: function(){
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {

        if(data.success)
        {

          var htmlString = '';

          $.each(data.dados, function(i, item){

            if(item.tabela == 'tbl_nota_compra')
            {
              tipo = 'Nota de Compra';
            }
            else
            {
              tipo = 'Nota de Serviço';
            }

            if(parseInt(item.estatus) == 1)
            {
              htmlString +=
               '<tr>'
                +'<td>'
                  +'<span class="text-success"><i data-feather="check-circle"></i></span>'
                +'</td>'
                +'<td>'+item.nota+'</td>'
                +'<td>'+item.cnpj+'</td>'
                +'<td>'+item.emissao+'</td>'
                +'<td>'+item.vencimento+'</td>'
                +'<td>R$ '+item.valor+'</td>'
                +'<td><span class="badge badge-primary">'+tipo+'</span></td>'
                +'<td>'
                  +'<div class="dropdown">'
                    +'<a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button"id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"aria-expanded="false">Ações</a>'
                    +'<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">'
                      +'<a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#estornaNotaModal" data-idnota="'+item.id_nota+'" data-tabela="'+item.tabela+'" data-nota="'+item.nota+'" data-cnpj="'+item.cnpj+'" data-valor="'+item.valor+'">Estornar</a>'
                    +'</div>'
                  +'</div>'
                +'</td>'
              +'</tr>';
            }
            else
            {
              htmlString +=
               '<tr class="text-secondary">'
                +'<td>'
                  +'<span class="text-danger"><i data-feather="x-circle"></i></span>'
                +'</td>'
                +'<td>'+item.nota+'</td>'
                +'<td>'+item.cnpj+'</td>'
                +'<td>'+item.emissao+'</td>'
                +'<td>'+item.vencimento+'</td>'
                +'<td>R$ '+item.valor+'</td>'
                +'<td><span class="badge badge-primary">'+tipo+'</span></td>'
                +'<td></td>'
              +'</tr>';
            }

          });

          $('#tbl_notaserecibos tbody').html(htmlString);

          feather.replace();

          var titulo = '';

          if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
          else titulo = data.dados.length + ' Registro Encontrado!'

          Swal.fire({
            title: titulo,
            type: 'success',
            timer: tempo_alerta_m,
            showConfirmButton: false
          })
        }
        else
        {

          $('#tbl_notaserecibos tbody').html('<tr class="odd"><td valign="top" colspan="7" class="text-center">Nenhum registro encontrado</td></tr>');

          Swal.fire({
            title: 'Não há registros!',
            type: 'warning',
            timer: tempo_alerta_m,
            showConfirmButton: false
          })
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  //
  $('#estornaNotaModal').on('show.bs.modal', function(event){

        var button = $(event.relatedTarget);
        var id = button.data('idnota');
        var nota = button.data('nota');
        var cnpj = button.data('cnpj');
        var valor = button.data('valor');
        var tabela = button.data('tabela');

        $('#id_entrada_nota').val(id);

        if (tabela = 'tbl_nota_compra')
        {
          tipo = 'Nota de Compra';
        }
        else
        {
          tipo = 'Nota de Serviço';
        }

        htmlString = 'Nº: ' + nota + '<br>Fornecedor: CNPJ ' + cnpj + '<br>Valor: R$ ' + valor + '<br><h5><span class="badge badge-primary">' + tipo + '</span></h5>';

        $('#estorna_nota').html(htmlString);

        $('#form-estornaNota input[name=id_nota]').val(id);
        $('#form-estornaNota input[name=tabela]').val(tabela);
  });

  // Formulário excluír Frota
  $('#form-estornaNota').submit(function(){

      var dados = $(this).serialize();

      $.ajax({
        type: 'post',
        url: base_url + '/notas/estornar',
        data: dados,
        dataType: 'json',
        beforeSend: function ()
        {
          Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen: () => {
              Swal.showLoading();
            },
            onClose:() => {
              Swal.fire({
                title: 'Ops...',
                text: 'Tempo de execução excedido!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
            }
          });
        },
        success: function(data)
        {
          if(data.success)
          {
            Swal.fire({
              title: 'Nota Estornada!',
              type: 'success',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
          else
          {
              Swal.fire({
                title: 'Não foi possível estornar a nota!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
          }
        }
      }).fail(function(){

          Swal.fire({
            title: 'Ops...',
            text: 'Houve uma falha inesperada!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });

      });

      return false;

    });

    // Valida administrador - Deletar Frota
    $('#notaEstornaValida').change(function(){

      if($(this).val() == "")
      {
        $('#notaEstornaValida').removeClass('is-valid').removeClass('is-invalid').val('');
        $('#btnEstorna_nota').prop('disabled', true);
      }

      var dados = {senha_atual: $(this).val()};

      $.ajax({
        type: 'post',
        url: base_url + '/usuario/validar',
        data: dados,
        dataType: 'json',
        success: function(data)
        {
          if(data.success)
          {
            $('#notaEstornaValida').removeClass('is-invalid').addClass('is-valid');
            $('#btnEstorna_nota').prop('disabled', false);
          }
          else
          {
            Swal.fire({
              title: 'Senha Incorreta!',
              type: 'error',
              showConfirmButton: false,
              timer: tempo_alerta_h,
              onClose: () => {
                $('#notaEstornaValida').removeClass('is-valid').addClass('is-invalid').val('');
                $('#btnEstorna_nota').prop('disabled', true);
              }
            });
          }
        }

      }).fail(function(){
        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          showConfirmButton: false,
          timer: tempo_alerta_h
        })
      });

      return false;

    });


  // Modal Editar Frota
  $('#historicoFornecedorModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var id = button.data('id');
    var cnpj = button.data('cnpj');
    var nome = button.data('nome');
    var telefone = button.data('telefone');

    $('#hs_cnpj_label').text(cnpj);
    $('#hs_razao_label').text(nome);
    $('#hs_telefone_label').text(telefone);

    $.ajax({
      type: 'get',
      url: base_url + '/fornecedores/historico/' + id,
      dataType: 'json',
      async: true,
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              type: 'error',
              onClose: () => {
                window.location.reload();
              }
            })
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          var htmlString = '';

          $.each(data.dados, function(i, item){

            if (item.tabela = 'tbl_nota_compra')
            {
              tipo = 'Nota de Compra';
            }
            else
            {
              tipo = 'Nota de Serviço';
            }

            htmlString +=
             '<tr>'
              +'<td>'+item.nota+'</td>'
              +'<td>'+item.emissao+'</td>'
              +'<td>'+item.vencimento+'</td>'
              +'<td>R$ '+item.valor+'</td>'
              +'<td><span class="badge badge-primary">'+tipo+'</span></td>'
            +'</tr>';

          });

          $('#tbl_hsfornecedor tbody').html(htmlString);

          Swal.fire({
            title: 'Dados carregados!',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            type: 'success'
          });
        }
      }
    });

  });

  // Modal Editar Frota
  $('#editarFornecedorModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var id = button.data('id');
    var cnpj = button.data('cnpj');
    var nome = button.data('nome');
    var telefone = button.data('telefone');

    $('#form-editarFornecedor input[name=id_fornecedor]').val(id);
    $('#form_editFornecedor_cnpj').val(cnpj);
    $('#form-editarFornecedor input[name=razao_social]').val(nome);
    $('#form-editarFornecedor input[name=telefone]').val(telefone);

  });

  // Modal Excluir Frota
  $('#excluirFornecedorModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var id = button.data('id');
    var cnpj = button.data('cnpj');
    var nome = button.data('nome');
    var telefone = button.data('telefone');

    $('#id_fornecedor_delete').val(id);
    $('#delete_fornecedor').html('CNPJ: '+cnpj+'<br>Razão Social: '+nome+'<br>Telefone: '+telefone);

  });

  // Modal Excluir Frota
  $('#restauraFornecedorModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var id = button.data('id');
    var cnpj = button.data('cnpj');
    var nome = button.data('nome');
    var telefone = button.data('telefone');

    $('#id_fornecedor_restaura').val(id);
    $('#restaura_fornecedor').html('CNPJ: '+cnpj+'<br>Razão Social: '+nome+'<br>Telefone: '+telefone);

  });

  // Formulário Editar Frota
  $('#form-editarFornecedor').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/fornecedores/editar',
      data: dados,
      dataType: 'json',
      beforeSend: function()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose: () => {
            Swal.fire({
              title: 'Tempo limite excedido!',
              timer: tempo_alerta_m,
              type: 'error',
              showConfirmButton: false,
              onClose:()=>{
                window.location.reload();
              }
            });
          }
        })
      },
      success: function(data)
      {
        if(data.success)
        {
          Swal.fire({
            title: 'Fornecedor Atualizado!',
            type: 'success',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
        else
        {
          Swal.fire({
            title: 'Não foi possível editar o fornecedor!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
      }
    }).fail(function(){
      Swal.fire({
        title: 'Houve uma falha inesperada!',
        type: 'error',
        timer: tempo_alerta_m,
        showConfirmButton: false,
        onClose: () => {
          window.location.reload();
        }
      })
    });

    return false;

  });

  // Formulário excluír Frota
  $('#form-excluirFornecedor').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/fornecedores/excluir',
      data: dados,
      dataType: 'json',
      beforeSend: function ()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose:() => {
            Swal.fire({
              title: 'Ops...',
              text: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          Swal.fire({
            title: 'Fornecedor Excluído!',
            type: 'success',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
        else
        {
            Swal.fire({
              title: 'Não foi possível excluir o fornecedor!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        });

    });

    return false;

  });

  // Valida administrador - Deletar Frota
  $('#fornecedorDeleteValida').change(function(){

    if($(this).val() == "")
    {
      $('#fornecedorDeleteValida').removeClass('is-valid').removeClass('is-invalid').val('');
      $('#btnDelete_fornecedor').prop('disabled', true);
    }

    var dados = {senha_atual: $(this).val()};

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/validar',
      data: dados,
      dataType: 'json',
      success: function(data)
      {
        if(data.success)
        {
          $('#fornecedorDeleteValida').removeClass('is-invalid').addClass('is-valid');
          $('#btnDelete_fornecedor').prop('disabled', false);
        }
        else
        {
          Swal.fire({
            title: 'Senha Incorreta!',
            type: 'error',
            showConfirmButton: false,
            timer: tempo_alerta_h,
            onClose: () => {
              $('#fornecedorDeleteValida').removeClass('is-valid').addClass('is-invalid').val('');
              $('#btnDelete_fornecedor').prop('disabled', true);
            }
          });
        }
      }

    }).fail(function(){
      Swal.fire({
        title: 'Ops...',
        text: 'Houve uma falha inesperada!',
        type: 'error',
        showConfirmButton: false,
        timer: tempo_alerta_h
      })
    })

  });

  // Formulário excluír Frota
  $('#form-restaurarFornecedor').submit(function(){

    var dados = $(this).serialize();

    $.ajax({
      type: 'post',
      url: base_url + '/fornecedores/restaurar',
      data: dados,
      dataType: 'json',
      beforeSend: function ()
      {
        Swal.fire({
          title: 'Carregando...',
          timer: tempo_loading,
          onBeforeOpen: () => {
            Swal.showLoading();
          },
          onClose:() => {
            Swal.fire({
              title: 'Ops...',
              text: 'Tempo de execução excedido!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
          }
        });
      },
      success: function(data)
      {
        if(data.success)
        {
          Swal.fire({
            title: 'Fornecedor Restaurado!',
            type: 'success',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });
        }
        else
        {
            Swal.fire({
              title: 'Não foi possível restaurar o fornecedor!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () => {
                window.location.reload();
              }
            });
        }
      }
    }).fail(function(){

        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        });

    });

    return false;

  });

  // Valida administrador - Deletar Frota
  $('#fornecedorRestauraValida').change(function(){

    if($(this).val() == "")
    {
      $('#fornecedorRestauraValida').removeClass('is-valid').removeClass('is-invalid').val('');
      $('#btnRestaura_fornecedor').prop('disabled', true);
    }

    var dados = {senha_atual: $(this).val()};

    $.ajax({
      type: 'post',
      url: base_url + '/usuario/validar',
      data: dados,
      dataType: 'json',
      success: function(data)
      {
        if(data.success)
        {
          $('#fornecedorRestauraValida').removeClass('is-invalid').addClass('is-valid');
          $('#btnRestaura_fornecedor').prop('disabled', false);
        }
        else
        {
          Swal.fire({
            title: 'Senha Incorreta!',
            type: 'error',
            showConfirmButton: false,
            timer: tempo_alerta_h,
            onClose: () => {
              $('#fornecedorRestauraValida').removeClass('is-valid').addClass('is-invalid').val('');
              $('#btnRestaura_fornecedor').prop('disabled', true);
            }
          });
        }
      }

    }).fail(function(){
      Swal.fire({
        title: 'Ops...',
        text: 'Houve uma falha inesperada!',
        type: 'error',
        showConfirmButton: false,
        timer: tempo_alerta_h
      })
    })

  });

  // Modal Excluir Frota
  $('#estornarManutencaoModal').on('show.bs.modal', function(event){

    var button = $(event.relatedTarget);
    var id = button.data('id');
    var placa = button.data('placa');
    var inicio = button.data('inicio');
    var termino = button.data('termino');

    $('#id_manutencao_estorna').val(id);
    $('#estorna_manutencao').html('Placa: '+placa+'<br>Início: '+inicio+'<br>Término: '+termino);

  });

  // Valida administrador - Deletar Frota
  $('#estornaManutencaoValida').change(function(){

      if($(this).val() == "")
      {
        $('#estornaManutencaoValida').removeClass('is-valid').removeClass('is-invalid').val('');
        $('#btnEstorna_manutencao').prop('disabled', true);
      }

      var dados = {senha_atual: $(this).val()};

      $.ajax({
        type: 'post',
        url: base_url + '/usuario/validar',
        data: dados,
        dataType: 'json',
        success: function(data)
        {
          if(data.success)
          {
            $('#estornaManutencaoValida').removeClass('is-invalid').addClass('is-valid');
            $('#btnEstorna_manutencao').prop('disabled', false);
          }
          else
          {
            Swal.fire({
              title: 'Senha Incorreta!',
              type: 'error',
              showConfirmButton: false,
              timer: tempo_alerta_h,
              onClose: () => {
                $('#estornaManutencaoValida').removeClass('is-valid').addClass('is-invalid').val('');
                $('#btnEstorna_manutencao').prop('disabled', true);
              }
            });
          }
        }

      }).fail(function(){
        Swal.fire({
          title: 'Ops...',
          text: 'Houve uma falha inesperada!',
          type: 'error',
          showConfirmButton: false,
          timer: tempo_alerta_h
        })
      })

    });

  // Formulário excluír Frota
  $('#form-estornarManutencao').submit(function(){

      var dados = $(this).serialize();

      $.ajax({
        type: 'post',
        url: base_url + '/manutencoes/estornar',
        data: dados,
        dataType: 'json',
        beforeSend: function ()
        {
          Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen: () => {
              Swal.showLoading();
            },
            onClose:() => {
              Swal.fire({
                title: 'Ops...',
                text: 'Tempo de execução excedido!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
            }
          });
        },
        success: function(data)
        {
          if(data.success)
          {
            Toast.fire({
              title: 'Manutenção Estornada!',
              type: 'success',
              onClose: () => {
                window.location.reload();
              }
            });
          }
          else
          {
              Swal.fire({
                title: 'Não foi possível excluir o fornecedor!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
          }
        }
      }).fail(function(){

          Swal.fire({
            title: 'Ops...',
            text: 'Houve uma falha inesperada!',
            type: 'error',
            timer: tempo_alerta_m,
            showConfirmButton: false,
            onClose: () => {
              window.location.reload();
            }
          });

      });

      return false;

    });

    // Valida placa do veículo/equipamento - Relatorios
    $('#relatorio_parametro').on('change', function(){

      var tipo = $('#gerarRelatorio select[name=tipo]').val();

      if (parseInt(tipo) == 1)
      {
        if ($(this).val() == "")
        {
          $(this).removeClass('is-invalid').removeClass('is-valid').val('');
          $('#btn_formPrev').prop('disabled', true).text('Aguardando Validação');
        }
        else
        {
          var dados = { placa: $(this).val() };

          $.ajax({
              type: 'post',
              url: base_url + '/frota/validar/',
              data: dados,
              dataType: 'json',
              success: function(data)
              {
                if(data.success)
                {
                  $('#relatorio_parametro').removeClass('is-invalid').addClass('is-valid');
                  $('#gerarRelatorio :submit').prop('disabled', false).text('Gerar Relatório');
                }
                else
                {
                  $('#relatorio_parametro').removeClass('is-valid').addClass('is-invalid');
                  $('#gerarRelatorio :submit').prop('disabled', true).text('Aguardando Validação');
                }
              }
          }).fail(function(){
            Swal.fire({
              title: 'Houve uma falha inesperada!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false
            }).then((result) => {
              $(this).val('');
            });
          });


          return false;
        }
      }
      else if(parseInt(tipo) == 2)
      {

      }
      else
      {

      }

    });

    $('#form-ajuda-login').submit(function(){

      var dados = $(this).serialize();

      $.ajax({
        type: 'post',
        url: base_url + '/sistema/ajuda',
        data: dados,
        dataType: 'json',
        beforeSend: function ()
        {
          Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen: () => {
              Swal.showLoading();
            },
            onClose:() => {
              Swal.fire({
                title: 'Ops...',
                text: 'Tempo de execução excedido!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
            }
          });
        },
        success: function(data)
        {
          if(data.success)
          {
            Swal.fire({
              title: 'Sua mensagem foi enviada!',
              text: 'Aguarde o contato do administrador no e-mail informado.',
              type: 'success',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () =>
              {
                  window.location.reload();
              }
            });
          }
          else if(data.success == false)
          {
            Swal.fire({
              title: 'Não foi possível enviar sua mensagem!',
              type: 'error',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              onClose: () =>
              {
                window.location.reload();
              }
            });
          }
        }
      }).fail(function(){
        Swal.fire({
          title: 'Houve uma falha inesperada!',
          timer: tempo_alerta_m,
          type: 'error',
          showConfirmButton: false,
          onClose: () =>
          {
            window.location.reload();
          }
        });
      });

      return false;

    });


    // Preenche o modal com os dados do usuário - Edit User Account

    $('#modalConfirmNotify').on('show.bs.modal',function(event){

      var button = $(event.relatedTarget);
      var id_nt = button.data('id');
      var placa = button.data('placa');
      var dt = button.data('dt');

      $('#nt_placa_label').html('');
      $('#nt_descricao_label').html('');
      $('#tbl_nt_config_man tbody').html('');

      $.ajax({
        type: 'get',
        url: base_url + '/manutencoes/listaRealizadas/' + placa + '/' + dt,
        dataType: 'json',
        async: true,
        beforeSend: function()
        {
          Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen: () => {
              Swal.showLoading();
            },
            onClose: () => {
              Swal.fire({
                title: 'Tempo limite excedido!',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                type: 'error',
                onClose: () => {
                  window.location.reload();
                }
              })
            }
          });
        },
        success: function(data)
        {
          if(data.success)
          {

            $('#nt_placa_label').text(data.veiculo[0]['placa']);
            $('#nt_descricao_label').text(data.veiculo[0]['descricao']);

            htmlString = '';

            $.each(data.dados, function(i, item){
              var nota = '';
              var custo = '';

              if(item.nota == '0') { nota = '<span class="badge badge-primary">Nota de Serviço</span>'; } else { nota = item.nota; }
              if(item.custo == '0') { custo = '<span class="badge badge-primary">Nota de Compras</span>'; } else { custo = item.custo; }

              htmlString +=
              '<tr style="cursor:pointer">'
                +'<input type="hidden" name="id_manutencao" value="'+item.id+'">'
                +'<td>'+item.id+'</td>'
                +'<td>'+item.hr_inicio+'</td>'
                +'<td>'+item.hr_fim+'</td>'
                +'<td>'+item.descricao+'</td>'
                +'<td>'+nota+'</td>'
                +'<td>R$ '+custo+'</td>'
                +'<td><span class="badge badge-primary">'+item.email+'</span></td>'
              +'</tr>';
            });

            $('#tbl_nt_config_man tbody').html(htmlString);

            Toast.fire({
              title: 'Dados Carregados!',
              type: 'success'
            })

          }
          else
          {
            Swal.fire({
              title: 'Não há registros!',
              timer: tempo_alerta_s,
              showConfirmButton: false,
              type: 'warning',
              onClose: () =>
              {
                $('#modalConfirmNotify').modal('hide');
              }
            })
          }
        }
      });


    });

    $('#form-confirmNotify').submit(function(){

      var dados = $(this).serialize();

      alert(dados);

    });

    $('#consultaProdutoModal').on('show.bs.modal', function(){

      $('#tbl_consultaProdutoModal tbody').html('');

      $.ajax({
        url: base_url + '/sistema/populaConsulta',
        dataType: 'json',
        async: true,
        success: function(data)
        {
          if(data.produtos.length != 0)
          {

            var htmlString = '';

            var count = 0;

            $.each(data.produtos, function(i, item){

              count ++;

              htmlString +=
              '<tr>'
                +'<td>'+count+'</td>'
                +'<td class="codigo">'+item.codigo+'</td>'
                +'<td>'+item.descricao+'</td>'
              +'</tr>';
            });

            $('#tbl_consultaProdutoModal tbody').html(htmlString);
          }
          else
          {
            Swal.fire({
              title: 'Não há registros!',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              type: 'warning',
              onClose: () =>
              {
                $('#consultaProdutoModal').modal('hide');
              }
            })
          }
        }
      });

    });

    $('#consultaFrotaModal').on('show.bs.modal', function(){

      $('#tbl_consultaFrota tbody').html('');

      $.ajax({
        url: base_url + '/sistema/populaConsulta',
        dataType: 'json',
        async: true,
        success: function(data)
        {
          if(data.frotas.length != 0)
          {

            var htmlString = '';

            var count = 0;

            $.each(data.frotas, function(i, item){

              count ++;

              htmlString +=
              '<tr>'
                +'<td class="placa">'+item.placa_numero+'</td>'
                +'<td>'+item.descricao+'</td>'
              +'</tr>';
            });

            $('#tbl_consultaFrota tbody').html(htmlString);

          }
          else
          {
            Swal.fire({
              title: 'Não há registros!',
              timer: tempo_alerta_m,
              showConfirmButton: false,
              type: 'warning',
              onClose: () =>
              {
                $('#consultaFrotaModal').modal('hide');
              }
            })
          }
        }
      });

    });

    // Realiza o filtro de produtos na consulta
    $('#form-filtraConsultaProduto').on('submit change', function(){

      var dados = $(this).serialize();

      $.ajax({
        type: 'post',
        url: base_url + '/produtos/pesquisar',
        data: dados,
        dataType: 'json',
        beforeSend: function(){
          Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen: () => {
              Swal.showLoading();
            },
            onClose: () => {
              Swal.fire({
                title: 'Tempo de execução excedido!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
            }
          });
        },
        success: function(data){

          if(data.success)
          {

            var htmlString = '';

            $.each(data.dados, function(i, item){

              htmlString +=
               '<tr>'
                +'<td class="codigo">'+item.codigo+'</td>'
                +'<td>'+item.descricao+'</td>'
                +'<td>'+item.unidade+'</td>'
              +'</tr>';

            });

            $('#tbl_consultaProdutoModal tbody').html(htmlString);


            var titulo = '';

            if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
            else titulo = data.dados.length + ' Registro Encontrado!'

            Toast.fire({
              title: titulo,
              type: 'success',
            })
          }
          else
          {
            $('#tbl_consultaProdutoModal tbody').html('<tr class="odd"><td valign="top" colspan="7" class="text-center">Nenhum registro encontrado</td></tr>');

            Swal.fire({
              title: 'Não há registros!',
              type: 'warning',
              timer: tempo_alerta_m,
              showConfirmButton: false
            });
          }
        }
      }).fail(function(){
        Swal.fire({
          title: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        })
      });

      return false;

    });

    // Realiza o filtro de produtos na consulta
    $('#form-filtraConsultaFrota').on('submit change', function(){

      var dados = $(this).serialize();

      $.ajax({
        type: 'post',
        url: base_url + '/frota/pesquisar',
        data: dados,
        dataType: 'json',
        beforeSend: function(){
          Swal.fire({
            title: 'Carregando...',
            timer: tempo_loading,
            onBeforeOpen: () => {
              Swal.showLoading();
            },
            onClose: () => {
              Swal.fire({
                title: 'Tempo de execução excedido!',
                type: 'error',
                timer: tempo_alerta_m,
                showConfirmButton: false,
                onClose: () => {
                  window.location.reload();
                }
              });
            }
          });
        },
        success: function(data){

          if(data.success)
          {

            var htmlString = '';

            $.each(data.dados, function(i, item){

              htmlString +=
               '<tr>'
                +'<td class="placa">'+item.placa+'</td>'
                +'<td>'+item.descricao+'</td>'
              +'</tr>';

            });

            $('#tbl_consultaFrota tbody').html(htmlString);


            var titulo = '';

            if(data.dados.length > 1) titulo = data.dados.length + ' Registros Encontrados!';
            else titulo = data.dados.length + ' Registro Encontrado!'

            Toast.fire({
              title: titulo,
              type: 'success',
            })
          }
          else
          {

            $('#tbl_consultaFrota tbody').html('<tr class="odd"><td valign="top" colspan="7" class="text-center">Nenhum registro encontrado</td></tr>');

            Swal.fire({
              title: 'Não há registros!',
              type: 'warning',
              timer: tempo_alerta_m,
              showConfirmButton: false
            })
          }
        }
      }).fail(function(){
        Swal.fire({
          title: 'Houve uma falha inesperada!',
          type: 'error',
          timer: tempo_alerta_m,
          showConfirmButton: false,
          onClose: () => {
            window.location.reload();
          }
        })
      });

      return false;

    });

});
