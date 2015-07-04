


function prepare (data){
    
           
        $(document).ready(function(){

            ids = {};

            ids['Informática Integrado'] = 'c1';
            ids['Manutenção e Suporte em Informática Integrado'] = 'c2';
            ids['Mineração Integrado'] = 'c3';
            ids['Petróleo e Gás Integrado'] = 'c4';
            ids['Manutenção e Suporte em Informática Subsequente'] = 'c5';
            ids['Mineração Subsequente'] = 'c6';
            ids['Construção de Edifícios Superior'] = 'c7';	
            ids['Física Superior'] = 'c8';
            ids['Letras em Língua Portuguesa Superior'] = 'c9';
            ids['Matemática Superior'] = 'c10';
            ids['Operação de Microcomputadores Proeja'] = 'c11';
            ids['Outro'] = 'c12';
            gs = {};

            gs['Especialização'] = 'g1';
            gs['Doutorado'] = 'g2';
            gs['Mestrado'] = 'g3';
            gs['Outro'] = 'g4';

            if (data.tipo == 'Aluno'){
                    document.getElementById("option1").checked = true;
                    document.getElementById(ids[data.curso]).selected = true;
                    document.getElementById('a'+data.ano_periodo).selected = true;
                    option1Click();
            }
            if (data.tipo == 'Professor'){
                    document.getElementById("option2").checked = true;
                    document.getElementById(gs[data.grau_academico]).selected = true;
                    option2Click();
            }
            if (data.tipo == 'Servidor'){
                    document.getElementById("option3").checked = true;
                    option3Click();
            }
        });
    
}