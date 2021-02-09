<table>
    <thead>
        <tr>
            <th>Código</th>
            <th>Nome</th>
            <th>Login</th>
            <th>Série</th>
            <th>Turma</th>
            <th>Turno</th>
            <th>Ensino</th>
            <th>Ativo</th>
            <th>Criação</th>
            <th>Última Atualização</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($alunos as $aluno)
        <tr>
            <td>{{$aluno->id}}</td>
            <td>{{$aluno->name}}</td>
            <td>{{$aluno->email}}</td>
            <td>{{$aluno->turma->serie}}</td>
            <td>{{$aluno->turma->turma}}</td>
            <td>{{$aluno->turma->turno}}</td>
            <td>{{$aluno->turma->ensino}}</td>
            <td>@if($aluno->ativo==1)Sim @else Não @endif</td>
            <td>{{$aluno->created_at->format('d/m/Y H:i') }}</td>
            <td>{{$aluno->updated_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>