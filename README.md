Profile do Drupal padrão usado na FFLCH

### TODO:

Configuração de idiomas

 - Habilitar módulos de traduções: language e interface translation
 - Adicionar língua pt-br: admin/config/regional/language/add
 - Colocar português Brasil como língua default
 - Remover o pt-br da língua default: admin/config/regional/language/detection/url
 - Configurar parâmetros de tradução admin/config/regional/translate/settings:
   - Verificar semanalmente
   - Drupal translation server and local files
   - Only overwrite imported translations, customized translations are kept

Formatação de Texto

 - Habilitar módulo book.
 - Em tipo de conteúdo, apagar “book page”.
 - Habilitar página básica para ser transformada em livro: admin/structure/book/settings
 - Deixar “Full Html” como primeiro na lista:  admin/config/content/formats
 - Dar permissão para o role ldap em full html: admin/config/content/formats/manage/full_html
 - Remover  Authenticated user de admin/config/content/formats/manage/basic_html
 - Instalar e habilitar módulo atributos de  link no ckeditor: editor_advanced_link (Advanced Link)
 - Instalar e habilitar módulo de upload de arquivo no ckeditor: editor_file (D8 Editor file upload)
 - admin/config/content/formats/manage/full_html:  habilitar o botão do editor_file
   - Habilitar extensões: txt, doc, docx, xls, xlsx, odt, ods, odp, ppt, pptx, pdf
   - Maximum file size: 5 MB
   - Instalar e habilitar módulo para tamanho de fonte: ckeditor_font
   - admin/config/content/formats/manage/full_html:
   - habilitar os 4 botões de alinhamento, underline, maximizar tela, identação, símbolos e tamanho da fonte

Segurança

  - Em admin/config/people/captcha desafio padrão: image
  - Em admin/config/people/captcha/captcha-points ativar captcha em todos forms
  - Não possibilitar auto-cadastro: Em admin/config/people/accounts configurar para somente administradores criarem novas contas
  - Desabilitar módulos do core: “Update Manager” e “Comentário”
  - Instalar Google Analytics
  - Criar uma custom permission em admin/people/custom-permissions/list e adicionar a rota admin/config/system/site-information. Dar permissão para o usuário ldap usar essa nova permissão.

Envio de E-mails:

 - Habilitar módulo smtp
 - Em admin/config/system/smtp
   - host: smtp.gmail.com
   - porta: 587
   - protocol: TLS
   - usuário: noreply.fflch@usp.br
   - senha: senha do noreply.fflch

Formato de datas

 - Verificar as opções de formato de datas disponíveis: admin/config/regional/date-time
 - Adicionar os formatos admin/config/regional/date-time/formats/add:
   - dia/mes/ano: d/m/Y
   - extenso: l, j \d\e F \d\e Y

Gestão de conteúdo

 - Instalar e habilitar módulo para clonar: entity_clone
 - Instalar e habilitar módulo conditional fields
 - Instalar e habilitar módulo pathauto  
 - Configuration -> URL alias (alternativas) -> padrões > -add padrões (patterns) ->[node:title]
 - Instalar e habilitar módulo form placeholder
 - Instalar e habilitar módulos (Fomulário Web): contact + contact_storage + contact_storage_export + contact_emails
  - Instalação do csv_serialization: composer require drupal/csv_serialization
  - No modelo deixar apenas Página Básica com Título, Corpo e Tags/Tag

Estilos

 - Habilitar módulo Asset
 - em admin/people/permissions em “Asset Injector” dar permissão ao FFLCH.
 - Habilitar módulo imce
 - em admin/config/media/imce dar permissão ao ldap de “admin profile” em ambos os tipos de arquivos.

Permissões:

 - Usar a barra de ferramentas da administração
 - Access the Content overview page
 - Ignorar controle de acesso de conteúdo
 - Administrar conteúdo
 - Marcar todas permissões de “Página Básica”
 - Access in-place editing
 - Pular CAPTCHA
 - Ver o tema administrativo
 - Clone all Conteúdo entities
 - Clone all Custom block entities
 - Clone all Contact form entities
 - Clone all Menu entities
 - Criar e editar URLs alternativas
 - Add content and child pages to books and manage their hierarchies.
 - Administrar esboços do livro
 - Criar novos livros
 - Administrar blocos
 - Administrar menus e itens de menu
 - Usar as páginas de administração e ajuda
 - Administrar temas
 - Administer Google Analytics
 - Administrar vocabulários e termos
 - Administrar formulários de contato e suas configurações
 - Manage contact form emails
 - Export contact form messages
 - Delete Conditional fields
 - Edit Conditional fields
 - View Conditional fields
 - Contact message: Administer display
 - Contact message: Administer fields
 - Contact message: Administer form display
 - Conteúdo: Administer display
 - Conteúdo: Administer fields
 - Conteúdo: Administer form display
 - Administrar tipos de conteúdo
 - Administrar Views
 - import content csv

Temas disponíveis

 - fflch_aegan
 - Jethro + customizar cores via css (azul bebê)
 - +Nexus Theme (candidato) -
 - +Paxton 8.x-1.3 + alterações

Bugs conhecidos

No aegir, ao migrar ou clonar um site, o diretório de tradução no campo “Interface translations directory” em admin/config/media/file-system não é atualizado.
