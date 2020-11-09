<?php
/**
 * This file is part of Servicios plugin for FacturaScripts
 * Copyright (C) 2020 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\Servicios\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;
use FacturaScripts\Core\Base\DataBase\DataBaseWhere;

/**
 * Description of ListServicioATOp
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class ListServicioATOp extends ListController
{

    /**
     * 
     * @return array
     */
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['menu'] = 'sales';
        $data['submenu'] = 'services';
        $data['title'] = 'operator_services';
        $data['icon'] = 'fas fa-headset';

        return $data;
    }

    protected function createViews()
    {
        $this->createViewsServices();
        $this->createViewsMachines();
    }

    /**
     * 
     * @param string $viewName
     */
    protected function createViewsMachines(string $viewName = 'ListMaquinaAT')
    {
        $this->addView($viewName, 'MaquinaAT', 'machines', 'fas fa-laptop-medical');
        $this->addOrderBy($viewName, ['idmaquina'], 'code', 2);
        $this->addOrderBy($viewName, ['fecha'], 'date');
        $this->addOrderBy($viewName, ['nombre'], 'name');
        $this->addOrderBy($viewName, ['referencia'], 'reference');
        $this->addSearchFields($viewName, ['idmaquina', 'nombre', 'numserie', 'referencia']);

        /// filters
        $this->addFilterPeriod($viewName, 'fecha', 'date', 'fecha');
        $this->addFilterAutocomplete($viewName, 'codcliente', 'customer', 'codcliente', 'clientes', 'codcliente', 'nombre');

        $agents = $this->codeModel->all('agentes', 'codagente', 'nombre');
        $this->addFilterSelect($viewName, 'codagente', 'agent', 'codagente', $agents);
    }

    /**
     * 
     * @param string $viewName
     */
    protected function createViewsServices(string $viewName = 'ListServicioATOp')
    {
        $this->addView($viewName, 'ServicioATOp', 'services', 'fas fa-headset');
        $this->addOrderBy($viewName, ['fecha', 'hora'], 'date', 2);
        $this->addOrderBy($viewName, ['idprioridad'], 'priority');
        $this->addOrderBy($viewName, ['idservicio'], 'code');
        $this->addSearchFields($viewName, ['descripcion', 'idservicio', 'observaciones']);

        /// filters
        $this->addFilterPeriod($viewName, 'fecha', 'date', 'fecha');
        $this->addFilterAutocomplete($viewName, 'codcliente', 'customer', 'codcliente', 'clientes', 'codcliente', 'nombre');
        
        $priority = $this->codeModel->all('serviciosat_prioridades', 'id', 'nombre');
        $this->addFilterSelect($viewName, 'idprioridad', 'priority', 'idprioridad', $priority);
        
        $agents = $this->codeModel->all('agentes', 'codagente', 'nombre');
        $this->addFilterSelect($viewName, 'codagente', 'agent', 'codagente', $agents);
        
        $users = [
            ['label' => $this->toolBox()->i18n()->trans('user-active'), 'where' => [new DataBaseWhere('nick', $this->user->nick)]],
            ['label' => $this->toolBox()->i18n()->trans('all'), 'where' => []]
          ];
        $this->addFilterSelectWhere($viewName, 'users', $users);
        
        $status = [
            ['label' => $this->toolBox()->i18n()->trans('Pendiente'), 'where' => [new DataBaseWhere('idestado', '1')]],
            ['label' => $this->toolBox()->i18n()->trans('all'), 'where' => []]
          ];
           $this->addFilterSelectWhere($viewName, 'status', $status);
    }

    /**
     * 
     * @param string $viewName
     */
}
