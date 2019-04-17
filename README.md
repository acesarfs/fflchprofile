## Profile do Drupal padrão usado na FFLCH


## mandatory

A pasta *mandatory* foi criada para forçar que algumas configurações
voltem aos valores defaults do nosso profile, para isso, há links na pasta mandatory
apontando para alguns arquivos .yml da pasta install.
Assim, mesmo que usuários tenham alterado essas configurações, pode-se rodar o seguinte
comando para obrigar o site a voltar para estado do momento da instalação:

    cd your-drupal-site
    ./vendor/bin/drush cim --partial --source='profiles/contrib/fflchprofile/config/mandatory'

### Configurações do editor:

 - Somente o full_html está disponível
 - Botão com atríbutos do link usando módulo editor_advanced_link
 - Botão de arquivo usando o módulo editor_file
 - Tamanho e tipo de fonte usando editor_font

Segurança

 - somente administradores podem criar novas contas
 - role fflch pode alterar nome do site

Módulos disponíveis na role fflch:

 - Google Analytics
 - Assets

Formato de datas disponíveis:

 - dia/mes/ano: d/m/Y
 - extenso: l, j \d\e F \d\e Y

Gestão de conteúdo

 - página básica (com url baseada no título)
 - clone dos nodes
 - conditional fields
 - webform
 - blocos
 - menus
 - views

Temas disponíveis:

 - fflch_aegan
 - fflch_jethro
 - fflch_nexus
 - fflch_paxton

## Roteiro para treinamento baseado no profile

### Tutorial 1 - Solicitando um site

 - O que é e por que Drupal
 - Como solicitar um site
 - Como acessar área de administração

### Tutorial 2 - Minha primeira Página

 - Como criar uma página estática
 - Opções do editor
   - upload de arquivos pdf, docx etc
   - upload e opções de imagem
   - link para externo
   - Endereço do node customizado
 - Manter versões das paǵinas com revisões
 - Clone de páginas

### Tutorial 3 - Blocos

 - O que é um bloco?
 - Regiões disponíveis e seus nomes
 - Mostrar bloco já criados
 - Desativar blocos
 - Criando blocos customizados
 - Posicionado bloco em alguma região
 - Regras de visibilidade de bloco na paǵinas
 - Configurar visibilidade do bloco com asterisco *

### Tutorial 4 - Menu

 - Gerenciando itens no menu principal do site
 - Alteração da ordem dos itens no menu principal
 - Criando um novo menu
 - Posicionamento do menu como bloco
 - Restringir a visibilidade do bloco de menu


##############  Organizar

 - Configurações básicas do site
 - Bloco como nome do site
 - Slideshow
 - Homepage dinâmica
 - Homepage fixa

Tutorial 6 - webform
Temas disponíveis. Tema padrão aegan.

google analytics

Tutorial 8 - Taxonomia
Tutorial 10 - Tipo de Conteúdo
Tutorial 11 - Campos Condicionais
Tutorial 12 - Views
Tutorial 13 - CSS injector
Tutorial 3 - Hierarquia de Páginas
Acessar páginas já criadas

Inserir página no Menu principal
Definir Página como estrutura de livro
Criação de subpáginas
Navegando entre as páginas
Alterando ordem das subpáginas

Lembretes para treinamento/tutoriais
Para inserir imagem com espaçamento, inserir legenda
Classificação de paǵinas usando taxonomia (dinâmica e estática)
Hierarquia de conteúdo com books
Criar regras de visibilidade usando o conditions fields







